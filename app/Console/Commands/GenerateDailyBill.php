<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateDailyBill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GenerateDailyBill:GenerateDailyBill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GenerateDailyBill';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {

            $userId = $user['user_id'];

            $billdetails = User::getUserRateLimiter($userId);

            $billdetails['amountDue'] = ($billdetails['extra_calls']*0.01). "$";  
            Log::info($user['name']. " This users usage has been logged". json_encode($billdetails));
        }
    }
}
