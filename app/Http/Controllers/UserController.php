<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BillingRecord;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    protected $user;

    public function __construct(User $user) {

        $this->user = $user;

    }

    /**
     * Register Users
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        $requestData = $request->all();
        $rules = [
            'name' => 'required|min:3|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'tier_id' => 'required|integer|between:1,3'
        ];
        
        $messages = [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 3 characters.',
            'name.max' => 'Name cannot exceed 20 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Password is required.',
            'tier_id.required' => 'Subscription tier is required.',
            'tier_id.between' => 'Tier ID must be between 1 and 3.',
        ];

        $validator = Validator::make($requestData, $rules, $messages);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'failed',
                'status_code' => 404,
                'errors' => $validator->errors()
            ], 404);
        }

        $user = $this->user->registerAndGetApiToken($requestData);
        
        if (!empty($user['token'])) {
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'token' => $user['token']
            ], 200);
        }
        else {
            return response()->json([
                'status' => 'success',
                'status_code' => 422,
                'error' => $user['error']
            ], 422);
        }
        
    }

    /**
     * User Need to login
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {

        $requestDate = $request->all();
        $login = $this->user->validateUser($requestDate);

        return response()->json(['token' => $login]);
    }

    /**
     * GetUserDetails
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetails(Request $request) {
        
        $requestData = $request->all();
        $user = Auth::user();

        $userDetails = User::getUserRateLimiter($user->user_id);
        $response = [];

        if ($userDetails) {
            $userDetails = $userDetails->toArray();
            $response = [
                'todayUsage' => $userDetails['apiHit'],
                'subscriptionPlanName' => Subscription::SUBSCRIPTION_OPTIONS[$userDetails['subscription_id']],
                'extraCallUsed' => $userDetails['extra_calls']
            ];
        }

        return response()->json($response);
    }
}