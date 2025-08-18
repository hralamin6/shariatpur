<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\HouseType;
use Illuminate\Database\Seeder;

class HouseSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure at least one type exists
        $typeId = HouseType::query()->inRandomOrder()->value('id') ?? null;
        if (!$typeId) {
            $typeId = HouseType::create([
                'user_id' => 1,
                'name' => 'Family Flat',
                'status' => 'active',
            ])->id;
        }

        $houses = [
            ['name' => 'Sunny Family Flat'],
            ['name' => 'Cozy Studio'],
            ['name' => 'Bachelor Room Near Market'],
            ['name' => 'Spacious Apartment'],
            ['name' => 'Shared Room for Students'],
        ];

        foreach ($houses as $row) {
            House::updateOrCreate(
                ['name' => $row['name']],
                [
                    'user_id' => rand(1, 2),
                    'house_type_id' => $typeId,
                    'upazila_id' => rand(322, 327),
                    'phone' => '01'.rand(3,9).rand(10000000, 99999999),
                    'details' => 'Nice house for rent with nearby amenities.',
                    'map_one' => null,
                    'area' => rand(600, 1200).' sqft',
                    'room_number' => rand(1, 5),
                    'bathroom_number' => rand(1, 3),
                    'address' => 'Popular area, Shariatpur',
                    'rent_price' => rand(5000, 25000),
                    'status' => 'active',
                ]
            );
        }
    }
}

