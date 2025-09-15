<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarType;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $types = CarType::pluck('id', 'name');

        $cars = [
            ['name' => 'City Sedan', 'type' => 'Sedan', 'ac' => true, 'seat' => 4, 'weight' => 200, 'price' => 2500],
            ['name' => 'Family SUV', 'type' => 'SUV', 'ac' => true, 'seat' => 7, 'weight' => 300, 'price' => 4000],
            ['name' => 'Airport Microbus', 'type' => 'Microbus', 'ac' => true, 'seat' => 12, 'weight' => 500, 'price' => 5000],
            ['name' => 'Cargo Pickup', 'type' => 'Pickup', 'ac' => false, 'seat' => 2, 'weight' => 1000, 'price' => 3500],
        ];

        foreach ($cars as $c) {
            $typeId = $types[$c['type']] ?? $types->first();
            Car::updateOrCreate(
                ['name' => $c['name']],
                [
                    'user_id' => 1,
                    'car_type_id' => $typeId,
                    'upazila_id' => rand(322, 327),
                    'driver_name' => 'test driver',
                    'phone' => '01'.rand(3, 9).rand(10000000, 99999999),
                    'ac' => (bool) $c['ac'],
                    'seat_number' => (int) $c['seat'],
                    'weight_capacity' => (int) $c['weight'],
                    'address' => 'Shariatpur City',
                    'map' => 'https://maps.google.com/?q='.urlencode($c['name']),
                    'rent_price' => (int) $c['price'],
                    'status' => 'active',
                ]
            );
        }
    }
}
