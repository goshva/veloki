<?php

namespace App\Services;

use App\Models\Price;
use Carbon\Carbon;

class BikeRentalCalculator
{
    /**
     * Calculate the total rental price for a bike
     *
     * @param string $bikeGroup mechanical|electric
     * @param Carbon $startTime
     * @param Carbon $endTime
     * @return float
     */
    public function calculatePrice(string $bikeGroup, Carbon $startTime, Carbon $endTime): float
    {
        // Check for special "until 20:00" pricing first
        $specialPrice = $this->checkSpecialUntil2000Pricing($bikeGroup, $startTime, $endTime);
        if ($specialPrice !== null) {
            return $specialPrice;
        }

        // Get all price periods for this bike group, ordered by period duration
        $prices = Price::where('bike_group', $bikeGroup)
            ->where('duration', '!=', 'until 20:00') // Exclude special pricing from regular calculation
            ->orderBy('period')
            ->get();

        if ($prices->isEmpty()) {
            throw new \InvalidArgumentException("No pricing found for bike group: {$bikeGroup}");
        }

        $totalPrice = 0;
        $remainingHours = $endTime->diffInHours($startTime, true);
        $remainingMinutes = $endTime->diffInMinutes($startTime, true) % 60;

        // Convert remaining time to hours with decimal points
        $remainingTime = $remainingHours + ($remainingMinutes / 60);

        // If zero duration, return 0
        if ($remainingTime <= 0) {
            return 0;
        }

        while ($remainingTime > 0) {
            // Find the most cost-effective price period that fits within the remaining time
            $bestPrice = null;
            $bestPriceRatio = PHP_FLOAT_MAX;

            foreach ($prices as $price) {
                // Skip if period is not positive number
                if ($price->period <= 0) {
                    continue;
                }

                // Skip if period is longer than remaining time
                if ($price->period > $remainingTime) {
                    continue;
                }

                // Calculate price per hour for this period
                $pricePerHour = $price->price / $price->period;

                // If this period has better price per hour ratio, use it
                if ($pricePerHour < $bestPriceRatio) {
                    $bestPrice = $price;
                    $bestPriceRatio = $pricePerHour;
                }
            }

            // If no suitable period found, use the smallest period and round up
            if ($bestPrice === null) {
                $smallestPeriod = $prices->first();
                $bestPrice = $smallestPeriod;
                $periods = ceil($remainingTime / $smallestPeriod->period);
                $totalPrice += $smallestPeriod->price * $periods;
                break;
            }

            // Calculate how many full periods we can fit
            $periodsToUse = floor($remainingTime / $bestPrice->period);
            $totalPrice += $bestPrice->price * $periodsToUse;
            $remainingTime -= $bestPrice->period * $periodsToUse;

            // Round remaining time to 4 decimal places to avoid floating point precision issues
            $remainingTime = round($remainingTime, 4);
        }

        return round($totalPrice, 2);
    }

    /**
     * Handle special case for "until 20:00" pricing
     *
     * @param string $bikeGroup
     * @param Carbon $startTime
     * @param Carbon $endTime
     * @return float|null
     */
    private function checkSpecialUntil2000Pricing(string $bikeGroup, Carbon $startTime, Carbon $endTime): ?float
    {
        $until2000Price = Price::where('bike_group', $bikeGroup)
            ->where('duration', 'until 20:00')
            ->first();

        if (!$until2000Price) {
            return null;
        }

        $endOfDay2000 = $startTime->copy()->setTime(20, 0, 0);

        // If start time is after 20:00 or end time is after 20:00, don't use special pricing
        if ($startTime->hour >= 20 || $endTime->hour >= 20 || $endTime->greaterThan($endOfDay2000)) {
            return null;
        }

        // If rental ends at or before 20:00, use special pricing
        return $until2000Price->price;
    }
}