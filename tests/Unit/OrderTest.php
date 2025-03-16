<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Bike;
use App\Models\Price;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test price records
        Price::create([
            'bike_group' => 'mechanical',
            'duration_hours' => 1,
            'price' => 400.00
        ]);
        
        Price::create([
            'bike_group' => 'mechanical',
            'duration_hours' => 3,
            'price' => 1000.00
        ]);
        
        Price::create([
            'bike_group' => 'mechanical',
            'duration_hours' => 24,
            'price' => 2000.00
        ]);

        // Special price for "until 20:00"
        Price::create([
            'bike_group' => 'mechanical',
            'duration_hours' => 0, // Represents "Until 20:00"
            'price' => 1500.00
        ]);
    }

    /** @test */
    public function it_calculates_correct_price_for_one_hour_rental()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 11:00:00'),
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $this->assertEquals(400, $order->calculatePrice());
    }

    /** @test */
    public function it_calculates_correct_price_for_three_hour_rental()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 13:00:00'),
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $this->assertEquals(1000, $order->calculatePrice());
    }

    /** @test */
    public function it_calculates_correct_price_for_until_2000_rental()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 20:00:00'),
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $this->assertEquals(1500, $order->calculatePrice());
    }

    /** @test */
    public function it_calculates_correct_price_for_multiple_bikes()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike1 = Bike::create(['group' => 'mechanical']);
        $bike2 = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 11:00:00'),
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach([$bike1->id, $bike2->id]);
        
        $this->assertEquals(800, $order->calculatePrice()); // 2 bikes * 400.00
    }

    /** @test */
    public function it_returns_zero_for_non_completed_orders()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-16 11:00:00'),
            'status' => 'pending'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $this->assertEquals(0, $order->calculatePrice());
    }

    /** @test */
    public function it_uses_24_hour_rate_for_longer_rentals()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => Carbon::parse('2025-03-16 10:00:00'),
            'end_time' => Carbon::parse('2025-03-17 10:00:00'),
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $this->assertEquals(2000, $order->calculatePrice());
    }
}