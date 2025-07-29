<?php

namespace App\Http\Middleware;

use App\Models\ApiRequestLog;
use App\Models\BillingRecord;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty($request->user()) && !empty($request->user()->user_id)) {

            $userId = $request->user()->user_id;
            $user = User::getUserRateLimiter($userId);

            if (!empty($user)) {
                $user = $user->toArray();
            }
            if (
                empty($user) || 
                (!empty($user['included_calls']) && !empty($user['apiHit']) && $user['included_calls'] >= $user['apiHit']) ||
                (!empty($user['subscription_id']) && $user['subscription_id'] == 3)
            ) {

                if ($user['subscription_id'] == 3 && $user['included_calls'] <= $user['apiHit']) {
                    BillingRecord::where('billing_record_id', $user['billing_record_id'])->update([
                        'extra_calls' => $user['extra_calls']+1
                    ]);
                }
                ApiRequestLog::create([
                    'user_id' => $userId,
                    'endpoint' => $request->path(),
                    'status_code' => 200,
                    'created_at' => now()
                ]);
            }
            else {
                 
                return response()->json([
                    'status' => 'failed',
                    'status_code' => 429,
                    'error' => 'Your Limit Reached'
                ], 429);
            }
        }
            return $next($request);
    }
}
