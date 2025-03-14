<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    protected $fillable = ['client_id', 'start_time', 'end_time', 'total_price', 'status', 'acceptor'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_price' => 'float',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function bikes()
    {
        return $this->belongsToMany(Bike::class, 'bike_order')->withTimestamps();
    }

    public function calculatePrice()
    {
        if (!$this->end_time) {
            // If the order is still active, estimate price based on current time
            $endTime = Carbon::now();
        } else {
            $endTime = Carbon::parse($this->end_time);
        }

        $startTime = Carbon::parse($this->start_time);
        $hours = $startTime->diffInHours($endTime);

        // Determine the applicable period
        $periods = [1, 3, 24]; // Defined rental periods
        $selectedPeriod = 24; // Default to 24 hours if exceeds all periods

        if ($hours <= 1) {
            $selectedPeriod = 1;
        } elseif ($hours <= 3) {
            $selectedPeriod = 3;
        } elseif ($hours <= 24) {
            $selectedPeriod = 24;
        }

        // Check "Until 20:00" condition
        $isUntil2000 = false;
        if ($startTime->isSameDay($endTime) && $endTime->hour <= 20) {
            $isUntil2000 = true;
        }

        $totalPrice = 0;

        foreach ($this->bikes as $bike) {
            $group = $bike->group ?? 'mechanical'; // Default to mechanical if not set

            // First, check for "Until 20:00" pricing if applicable
            if ($isUntil2000) {
                $priceRecord = Price::where('bike_group', $group)
                    ->where('duration_hours', 0) // 0 represents "Until 20:00"
                    ->first();
                if ($priceRecord) {
                    $totalPrice += $priceRecord->price;
                    continue; // Skip to next bike
                }
            }

            // Otherwise, use the selected period
            $priceRecord = Price::where('bike_group', $group)
                ->where('duration_hours', $selectedPeriod)
                ->first();

            if ($priceRecord) {
                $totalPrice += $priceRecord->price;
            } else {
                // Fallback: Use the highest available period price
                $fallbackPrice = Price::where('bike_group', $group)
                    ->orderBy('duration_hours', 'desc')
                    ->first();
                $totalPrice += $fallbackPrice ? $fallbackPrice->price : 0;
            }
        }

        $this->total_price = $totalPrice;
        $this->save();

        return $totalPrice;
    }
}