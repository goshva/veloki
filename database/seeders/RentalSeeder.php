<?php
namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    public function run()
    {
        // Example data (parsed manually from your document)
        $data = [
            [
                'name' => '2в',
                'phone' => null,
                'start_time' => '10:09:00',
                'end_time' => '17:35:00',
                'amount' => 800.0,
                'payment_method' => 'Рома',
                'net_profit' => 700.0,
                'cash' => 800.0,
                'card_sasha' => null,
                'card_misha' => null,
                'card_roma' => null,
                'entry_date' => '2025-03-13', // Adjust based on context
            ],
            // Add more rows as needed...
        ];

        foreach ($data as $item) {
            Rental::create($item);
        }
    }
}