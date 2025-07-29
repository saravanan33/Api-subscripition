<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingRecord extends Model
{
    /** @use HasFactory<\Database\Factories\BillingRecordFactory> */
    use HasFactory;

    protected $table = 'billing_records';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'included_calls',
        'extra_calls',
        'amount',
        'billed_at',
        'validity_start',
        'validity_end'
    ];

    protected $primaryKey = 'billing_record_id';

    public function subscription() {

        return $this->hasOne(Subscription::class, 'subscription_id', 'subscription_id');
    }

    public static function inserSubscription($userId, $tierId) {

        $subscription = Subscription::where('subscription_id', $tierId)->first();

        if ($subscription) {

            self::create([
                'user_id' => $userId,
                'subscription_id' => $tierId,
                'included_calls' => $subscription->daily_limit,
                'extra_calls' => 0,
                'amount' => 0,
                'validity_start' => date('Y-m-d'),
                'validity_end' => date('Y-m-d', strtotime('+30 days')),
                'billed_at' => date('Y-m-d'),
            ]);
        }

    }
}
