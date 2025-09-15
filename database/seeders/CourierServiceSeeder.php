<?php

namespace Database\Seeders;

use App\Models\CourierService;
use Illuminate\Database\Seeder;

class CourierServiceSeeder extends Seeder
{
    public function run(): void
    {
        $couriers = [
            'Sundarban Courier Service',
            'SA Paribahan Courier',
            'Janani Courier',
            'Steadfast Courier',
            'Pathao Courier',
            'RedX Courier',
            'Paperfly Courier',
            'eCourier',
            'Continental Courier',
            'Tiger Courier',
        ];

        foreach ($couriers as $name) {
            CourierService::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'upazila_id' => rand(322, 327),
                    'phone' => '01'.rand(3, 9).rand(10000000, 99999999),
                    'address' => $name.', Shariatpur',
                    'map' => 'https://maps.google.com/?q='.urlencode($name.' Shariatpur'),
                    'status' => 'active',
                ]
            );
        }
    }
}
