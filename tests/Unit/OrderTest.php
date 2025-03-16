<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up basic pricing data
        $this->setupPrices();
    }

    protected function setupPrices(): void
    {
        Price::create([
            'bike_group' => 'mechanical',
            'period' => 1,
            'duration' => '1 hour',
            'price' => 400
        ]);

        Price::create([
            'bike_group' => 'mechanical',
            'period' => 3,
            'duration' => '3 hours',
            'price' => 1000
        ]);

        Price::create([
            'bike_group' => 'mechanical',
            'period' => 24,
            'duration' => '24 hours',
            'price' => 2000
        ]);

        Price::create([
            'bike_group' => 'mechanical',
            'period' => 0,
            'duration' => 'until 20:00',
            'price' => 1500
        ]);
    }

    /** @test */
    public function it_calculates_correct_price_for_one_hour_rental()
    {
        $order = Order::factory()->create([
            'bike_group' => 'mechanical',
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 11:00:00'),
            'status' => 'completed'
        ]);

        $this->assertEquals(400, $order->calculateTotalPrice());
    }

    /** @test */
    public function it_calculates_correct_price_for_three_hour_rental()
    {
        $order = Order::factory()->create([
            'bike_group' => 'mechanical',
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 13:00:00'),
            'status' => 'completed'
        ]);

        $this->assertEquals(1000, $order->calculateTotalPrice());
    }

    /** @test */
    public function it_calculates_correct_price_for_until_2000_rental()
    {
        $order = Order::factory()->create([
            'bike_group' => 'mechanical',
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 20:00:00'),
            'status' => 'completed'
        ]);

        $this->assertEquals(1500, $order->calculateTotalPrice());
    }

    /** @test */
    public function it_calculates_correct_price_for_multiple_bikes()
    {
        $order = Order::factory()->create([
            'bike_group' => 'mechanical',
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 11:00:00'),
            'status' => 'completed',
            'quantity' => 2
        ]);

        $this->assertEquals(800, $order->calculateTotalPrice());
    }

    /** @test */
    public function it_returns_zero_for_non_completed_orders()
    {
        $order = Order::factory()->create([
            'bike_group' => 'mechanical',
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 11:00:00'),
            'status' => 'pending'
        ]);

        $this->assertEquals(0, $order->calculateTotalPrice());
    }

    /** @test */
    public function it_uses_24_hour_rate_for_longer_rentals()
    {
        $order = Order::factory()->create([
            'bike_group' => 'mechanical',
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-17 10:00:00'),
            'status' => 'completed'
        ]);

        $this->assertEquals(2000, $order->calculateTotalPrice());
    }
}