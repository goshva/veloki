<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['bike_group', 'duration', 'period', 'price'];
}