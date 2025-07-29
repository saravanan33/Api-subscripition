<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'subscription_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    public function billingRecords() {

        return $this->hasMany(Subscription::class, 'user_id', 'user_id');
    }

    public function registerAndGetApiToken($userDetails)
    {

        try {

            DB::beginTransaction();
            $user = User::create([
                'name' => $userDetails['name'],
                'email' => $userDetails['email'],
                'password' => Hash::make($userDetails['password']),
            ]);

            BillingRecord::inserSubscription($user->user_id, $userDetails['tier_id']);
            DB::commit();
            return ['token' => $this->getUserToken($user->user_id)];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    private function getUserToken($userId)
    {

        $token = User::find($userId)->createToken('api-token')->plainTextToken;

        $token = explode('|', $token);

        return $token[1] ?? '';
    }

    public function validateUser($input)
    {

        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        $userDetails = User::where('email', $email)->first();
        $token = '';
        if (
            !empty($userDetails) &&
            !empty($userDetails->password) &&
            Hash::check($password, $userDetails->password)
        ) {
            $token =  $this->getUserToken($userDetails->user_id);   
        }

        return $token;
    }

    public static function getUserRateLimiter($userId) {
         return   User::from('users as u')
                ->select(
                    DB::raw('COUNT(arl.api_request_log_id) as apiHit'),
                    DB::raw('MAX(br.included_calls) as included_calls'),
                    DB::raw('MAX(br.subscription_id) as subscription_id'),
                    DB::raw('MAX(br.billing_record_id) as billing_record_id'),
                    DB::raw('MAX(br.extra_calls) as extra_calls')
                )
                ->join('billing_records as br', 'br.user_id', '=', 'u.user_id')
                ->join('api_request_logs as arl', 'arl.user_id', '=', 'u.user_id')
                ->whereDate('br.validity_start', '<=', date('Y-m-d'))
                ->whereDate('br.validity_end', '>=', date('Y-m-d'))
                ->whereDate('arl.created_at', Carbon::today())
                ->where('u.user_id', $userId)
                ->groupBy('u.user_id')
                ->first();

        
    }
}
