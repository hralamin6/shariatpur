<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $restaurants = [
            'শরীয়তপুর ফুড কর্নার',
            'জাজিরা বিরিয়ানি হাউস',
            'ভেদরগঞ্জ কিচেন',
            'গোসাইরহাট ঢাবা',
            'নড়িয়া স্পাইস',
            'ডামুড্যা টেস্টি টেবিল',
        ];

        foreach ($restaurants as $name) {
            Restaurant::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => 1,
                    'upazila_id' => rand(322, 327),
                    'phone' => '01'.rand(3,9).rand(10000000, 99999999),
                    'address' => $name . ' এলাকায়, শরীয়তপুর',
                    'map' => 'https://maps.google.com/?q=' . urlencode($name),
                    'details' => 'সুবিধাসমূহ: সুস্বাদু খাবার, আরামদায়ক পরিবেশ, পরিবার-বান্ধব।',
                    'status' => 'active',
                ]
            );
        }
    }
}

