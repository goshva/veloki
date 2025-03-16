<?php

namespace App\Services;

use App\Models\Price;
use Carbon\Carbon;

class BikeRentalCalculator
{
    public function calculatePrice(string $bikeGroup, Carbon $startTime, Carbon $endTime): float
    {
        // Check if rental ends at or before 20:00
        $endTimeHour = $endTime->format('H');
        $isEndOfDaySpecial = $endTimeHour <= 20 && $endTime->format('H:i') <= '20:00';
        
        if ($isEndOfDaySpecial) {
            // Try to get special "until 20:00" price
            $specialPrice = Price::where('bike_group', $bikeGroup)
                ->where('duration_hours', 0)
                ->where('period', 'special')
                ->first();
                
            if ($specialPrice) {
                return $specialPrice->price;
            }
        }
        
        // Calculate duration in hours
        $duration = $endTime->diffInHours($startTime);
        
        // Get regular hourly price
        $price = Price::where('bike_group', $bikeGroup)
            ->where('duration_hours', 1)
            ->where('period', 'hour')
            ->first();
            
        return $price ? $price->price * $duration : 0;
    }
}