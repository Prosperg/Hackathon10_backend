<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketCategory;

class TicketCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Demi-journée',
                'description' => 'Ticket valable pour 12 heures',
                'price' => 300,
                'duration_hours' => 12,
                'is_active' => true
            ],
            [
                'name' => 'Journée',
                'description' => 'Ticket valable pour 24 heures',
                'price' => 500,
                'duration_hours' => 24,
                'is_active' => true
            ],
            [
                'name' => 'Nuit',
                'description' => 'Ticket valable de 18h à 8h (14 heures)',
                'price' => 400,
                'duration_hours' => 14,
                'is_active' => true
            ],
            [
                'name' => 'Week-end',
                'description' => 'Ticket valable pour 48 heures (samedi et dimanche)',
                'price' => 800,
                'duration_hours' => 48,
                'is_active' => true
            ],
            [
                'name' => 'Semaine',
                'description' => 'Ticket valable pour 7 jours',
                'price' => 2500,
                'duration_hours' => 168, // 7 * 24
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            TicketCategory::create($category);
        }
    }
}
