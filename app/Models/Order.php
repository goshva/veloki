<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        // Check if the order status is completed
        if ($this->status !== 'completed') {
            Log::warning("Attempted to calculate price for a non-completed order ID $this->id.");
            return 0; // Return 0 or handle as needed for non-completed orders
        }

        $endTime = Carbon::parse($this->end_time);
        $startTime = Carbon::parse($this->start_time);
        $selectedPeriod = $this->determineRentalPeriod($startTime, $endTime);
        $bikeGroups = $this->countBikesByGroup();

        $totalPrice = $this->calculateTotalPrice($bikeGroups, $selectedPeriod, $startTime, $endTime);

        $this->total_price = $totalPrice;
        $this->save();

        Log::info("Total calculated price for order ID $this->id: $totalPrice");

        return $totalPrice;
    }

    private function determineRentalPeriod(Carbon $startTime, Carbon $endTime)
    {
        $hours = $startTime->diffInHours($endTime);
        $periods = [1, 3, 24]; // Defined rental periods

        if ($hours <= 1) {
            return 1;
        } elseif ($hours <= 3) {
            return 3;
        } elseif ($hours <= 24) {
            return 24;
        }

        return 24; // Default to 24 hours if exceeds all periods
    }

    private function countBikesByGroup()
    {
        $bikeGroups = [];

        foreach ($this->bikes as $bike) {
            $group = $bike->group ?? 'mechanical'; // Default to mechanical if not set
            if (!isset($bikeGroups[$group])) {
                $bikeGroups[$group] = 0;
            }
            $bikeGroups[$group]++;
        }

        return $bikeGroups;
    }

    private function calculateTotalPrice(array $bikeGroups, int $selectedPeriod, Carbon $startTime, Carbon $endTime)
    {
        $totalPrice = 0;
        $isUntil2000 = $startTime->isSameDay($endTime) && $endTime->hour <= 20;

        foreach ($bikeGroups as $group => $count) {
            $price = $this->getPriceByPeriodAndGroup($selectedPeriod, $group, $isUntil2000);
            $totalPrice += $price * $count;
            Log::info("Price for bike group $group for $selectedPeriod hours: $price, Count: $count, Total: " . ($price * $count));
        }

        return $totalPrice;
    }

    private function getPriceByPeriodAndGroup(int $period, string $group, bool $isUntil2000)
    {
        if ($isUntil2000) {
            $priceRecord = Price::where('bike_group', $group)
                ->where('duration_hours', 0) // 0 represents "Until 20:00"
                ->first();
            if ($priceRecord) {
                return $priceRecord->price;
            }
        }

        $priceRecord = Price::where('bike_group', $group)
            ->where('duration_hours', $period)
            ->first();

        if ($priceRecord) {
            return $priceRecord->price;
        }

        // Fallback: Use the highest available period price
        $fallbackPrice = Price::where('bike_group', $group)
            ->orderBy('duration_hours', 'desc')
            ->first();

        return $fallbackPrice ? $fallbackPrice->price : 0;
    }
}
