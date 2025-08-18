<?php

namespace Database\Seeders;

use App\Models\HouseType;
use Illuminate\Database\Seeder;

class HouseTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Family Flat',
            'Sublet',
            'Bachelor Room',
            'Shared Room',
            'Full House',
            'Studio',
            'Apartment',
            'Single Room',
        ];

        foreach ($types as $name) {
            HouseType::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'status' => 'active',
                ]
            );
        }
    }
}

