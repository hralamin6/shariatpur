<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            'শরীয়তপুর গ্র্যান্ড হোটেল',
            'জাজিরা প্লাজা হোটেল',
            'ভেদরগঞ্জ ইন',
            'গোসাইরহাট রেসিডেন্সি',
            'নড়িয়া কনটিনেন্টাল',
            'ডামুড্যা রয়্যাল',
        ];

        foreach ($hotels as $name) {
            Hotel::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => 1,
                    'upazila_id' => rand(322, 327),
                    'phone' => '01'.rand(3,9).rand(10000000, 99999999),
                    'address' => $name . ' এলাকায়, শরীয়তপুর',
                    'map' => 'https://maps.google.com/?q=' . urlencode($name),
                    'details' => 'সুবিধাসমূহ: আরামদায়ক রুম, রেস্টুরেন্ট, ওয়াই-ফাই।',
                    'status' => 'active',
                ]
            );
        }
    }
}

