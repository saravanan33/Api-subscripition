<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscription_tiers')->insert([
            ['name' => 'Free', 'daily_limit' => 100],
            ['name' => 'Standard', 'daily_limit' => 1000],
            ['name' => 'Premium', 'daily_limit' => 10000],
        ]);
    }
}
