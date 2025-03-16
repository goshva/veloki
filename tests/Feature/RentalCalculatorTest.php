<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Price;
use Carbon\Carbon;

class RentalCalculatorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
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
    }

    #[Test] // Using attribute instead of doc-comment
    public function it_can_calculate_rental_price_through_api()
    {
        $response = $this->postJson('/api/calculate-rental', [
            'bike_group' => 'mechanical',
            'start_time' => '2025-03-16 10:00:00',
            'end_time' => '2025-03-16 20:00:00'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'price' => 1500.00
                ]);
    }
}