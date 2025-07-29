<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    /** @use HasFactory<\Database\Factories\ApiRequestLogFactory> */
    use HasFactory;

    protected $table = 'api_request_logs';

    protected $fillable = [
        'user_id',
        'endpoint',
        'status_code',
        'created_at',
        'updated_at',

    ];

    protected $primaryKey = 'api_request_log_id';
}
