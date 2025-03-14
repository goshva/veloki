<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bike extends Model
{
    protected $fillable = ['name', 'description', 'group'];

    protected $casts = [
        'group' => 'string',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'bike_order')->withTimestamps();
    }
}