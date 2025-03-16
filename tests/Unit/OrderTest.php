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
            'price' => 10.00
        ]);
        
        Price::create([
            'bike_group' => 'mechanical',
            'duration_hours' => 3,
            'price' => 25.00
        ]);
        
        Price::create([
            'bike_group' => 'mechanical',
            'duration_hours' => 24,
            'price' => 50.00
        ]);

        // Special price for "until 20:00"
        Price::create([
            'bike_group' => 'mechanical',
            'duration_hours' => 0, // Represents "Until 20:00"
            'price' => 35.00
        ]);
    }

    /** @test */
    public function it_calculates_correct_price_for_one_hour_rental()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => '2025-03-16 10:00:00',
            'end_time' => '2025-03-16 11:00:00',
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $price = $order->calculatePrice();
        
        $this->assertEquals(10.00, $price);
    }

    /** @test */
    public function it_calculates_correct_price_for_three_hour_rental()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => '2025-03-16 10:00:00',
            'end_time' => '2025-03-16 13:00:00',
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $price = $order->calculatePrice();
        
        $this->assertEquals(25.00, $price);
    }

    /** @test */
    public function it_calculates_correct_price_for_until_2000_rental()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => '2025-03-16 10:00:00',
            'end_time' => '2025-03-16 20:00:00',
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $price = $order->calculatePrice();
        
        $this->assertEquals(35.00, $price);
    }

    /** @test */
    public function it_calculates_correct_price_for_multiple_bikes()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike1 = Bike::create(['group' => 'mechanical']);
        $bike2 = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => '2025-03-16 10:00:00',
            'end_time' => '2025-03-16 11:00:00',
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach([$bike1->id, $bike2->id]);
        
        $price = $order->calculatePrice();
        
        $this->assertEquals(20.00, $price); // 2 bikes * 10.00
    }

    /** @test */
    public function it_returns_zero_for_non_completed_orders()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => '2025-03-16 10:00:00',
            'end_time' => '2025-03-16 11:00:00',
            'status' => 'pending'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $price = $order->calculatePrice();
        
        $this->assertEquals(0, $price);
    }

    /** @test */
    public function it_uses_24_hour_rate_for_longer_rentals()
    {
        $client = Client::create(['name' => 'Test Client']);
        $bike = Bike::create(['group' => 'mechanical']);
        
        $order = Order::create([
            'client_id' => $client->id,
            'start_time' => '2025-03-16 10:00:00',
            'end_time' => '2025-03-17 10:00:00',
            'status' => 'completed'
        ]);
        
        $order->bikes()->attach($bike->id);
        
        $price = $order->calculatePrice();
        
        $this->assertEquals(50.00, $price);
    }
}