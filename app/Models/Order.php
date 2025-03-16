<?php

namespace App\Models;

use App\Services\BikeRentalCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\OrderFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'bike_group',
        'start_time',
        'end_time',
        'status',
        'quantity'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'quantity' => 'integer'
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return OrderFactory::new();
    }

    public function calculateTotalPrice(): float
    {
        if ($this->status !== 'completed') {
            return 0;
        }

        $calculator = new BikeRentalCalculator();
        $unitPrice = $calculator->calculatePrice(
            $this->bike_group,
            $this->start_time,
            $this->end_time
        );

        return $unitPrice * ($this->quantity ?? 1);
    }
}