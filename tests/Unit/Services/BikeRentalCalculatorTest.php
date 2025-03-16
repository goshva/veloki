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