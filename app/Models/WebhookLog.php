<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $fillable = [
        'sheet_name', 'range', 'values', 'timestamp', 'user', 'secret'
    ];

    protected $casts = [
        'values' => 'array',
        'timestamp' => 'datetime',
    ];
}