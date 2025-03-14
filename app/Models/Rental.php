<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'name', 'phone', 'start_time', 'end_time', 'amount', 'payment_method',
        'net_profit', 'cash', 'card_sasha', 'card_misha', 'card_roma', 'entry_date'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'entry_date' => 'date',
    ];
}