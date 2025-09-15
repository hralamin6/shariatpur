<?php

namespace Database\Seeders;

use App\Models\CarType;
use Illuminate\Database\Seeder;

class CarTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Sedan',
            'SUV',
            'Microbus',
            'Pickup',
            'Minivan',
            'Hatchback',
        ];

        foreach ($types as $name) {
            CarType::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => 1,
                    'status' => 'active',
                ]
            );
        }
    }
}
