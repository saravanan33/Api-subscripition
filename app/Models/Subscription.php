<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    use HasFactory;
    protected $table = 'subscription_tiers';

    protected $primaryKey = 'subscription_id';
    protected $fillable = [
        'name',
        'daily_limit'
    ];

    const SUBSCRIPTION_OPTIONS = [
        '1' => 'Free',
        '2' => 'Standard',
        '3' => 'Premium'
    ];
}
