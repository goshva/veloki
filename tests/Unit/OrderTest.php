<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Bike;
use App\Models\Price;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\BikeRentalCalculator;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new BikeRentalCalculator();

        // Set up test prices
        Price::create([
            'bike_group' => 'mechanical',
            'duration_hours' => 1,
            'price' => 400.00,
            'period' => 'hour'
        ]);

        Price::create([
            'bike_group' => 'mechanical',
            'duration_hours' => 0,
            'price' => 1500.00,
            'period' => 'special'
        ]);

        Price::create([
            'bike_group' => 'electric',
            'duration_hours' => 0,
            'price' => 2000.00,
            'period' => 'special'
        ]);
    }

    /** @test */
    public function it_calculates_price_for_end_of_day_special_mechanical()
    {
        $startTime = Carbon::parse('2025-03-16 10:00:00');
        $endTime = Carbon::parse('2025-03-16 20:00:00');

        $price = $this->calculator->calculatePrice('mechanical', $startTime, $endTime);
        
        // Should use "until 20:00" special price
        $this->assertEquals(1500, $price);
    }

    /** @test */
    public function it_calculates_price_for_end_of_day_special_electric()
    {
        $startTime = Carbon::parse('2025-03-16 10:00:00');
        $endTime = Carbon::parse('2025-03-16 20:00:00');

        $price = $this->calculator->calculatePrice('electric', $startTime, $endTime);
        
        // Should use "until 20:00" special price
        $this->assertEquals(2000, $price);
    }

    /** @test */
    public function it_uses_regular_pricing_when_rental_extends_past_2000()
    {
        $startTime = Carbon::parse('2025-03-16 10:00:00');
        $endTime = Carbon::parse('2025-03-16 21:00:00');

        $price = $this->calculator->calculatePrice('mechanical', $startTime, $endTime);
        
        // Should use regular pricing instead of "until 20:00" special price
        $this->assertNotEquals(1500, $price);
    }
}