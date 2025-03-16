<?php

namespace App\Services;

use App\Models\Price;
use DateTime;
use Illuminate\Support\Collection;

class BikeRentalCalculator
{
    private Collection $mechanicalPrices;
    private Collection $electricPrices;

    public function __construct()
    {
        $allPrices = Price::all();
        $this->mechanicalPrices = $allPrices->where('bike_group', 'mechanical');
        $this->electricPrices = $allPrices->where('bike_group', 'electric');
    }

    public function calculateTotalPrice(
        int $mechanicalBikesCount,
        int $electricBikesCount,
        DateTime $startTime,
        DateTime $endTime
    ): float {
        $duration = $endTime->diff($startTime);
        $totalHours = $duration->h + ($duration->days * 24);
        
        $mechanicalPrice = $this->getLowestPrice($totalHours, $this->mechanicalPrices, $startTime, $endTime);
        $electricPrice = $this->getLowestPrice($totalHours, $this->electricPrices, $startTime, $endTime);
        
        return ($mechanicalPrice * $mechanicalBikesCount) + ($electricPrice * $electricBikesCount);
    }

    private function getLowestPrice(
        int $hours,
        Collection $prices,
        DateTime $startTime,
        DateTime $endTime
    ): float {
        $candidates = collect();
        
        // Check if rental extends past 20:00
        $endingToday = clone $startTime;
        $endingToday->setTime(20, 0);
        
        // If rental is within the same day and ends before 20:00
        if ($endTime <= $endingToday) {
            $untilEveningPrice = $prices->firstWhere('duration', 'until 20:00');
            if ($untilEveningPrice) {
                $candidates->push($untilEveningPrice->price);
            }
        }

        // Add applicable hourly rates
        foreach ($prices as $price) {
            if (str_contains(strtolower($price->duration), 'hour')) {
                $durationHours = (int) filter_var($price->duration, FILTER_SANITIZE_NUMBER_INT);
                if ($hours <= $durationHours) {
                    $candidates->push($price->price);
                }
            }
        }
        
        // Check 24-hour rate
        $dayRate = $prices->firstWhere('duration', '24 hours');
        if ($dayRate) {
            if ($hours <= 24) {
                $candidates->push($dayRate->price);
            } else {
                // If rental is longer than 24 hours, calculate multiple days
                $days = ceil($hours / 24);
                $candidates->push($dayRate->price * $days);
            }
        }
        
        return $candidates->min() ?? 0;
    }
}