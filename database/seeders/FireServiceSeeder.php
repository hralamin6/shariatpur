<?php

namespace Database\Seeders;

use App\Models\FireService;
use Illuminate\Database\Seeder;

class FireServiceSeeder extends Seeder
{
    public function run(): void
    {
        $stations = [
            'শরীয়তপুর ফায়ার সার্ভিস ও সিভিল ডিফেন্স',
            'গোসাইরহাট ফায়ার সার্ভিস',
            'ভেদরগঞ্জ ফায়ার সার্ভিস',
            'জাজিরা ফায়ার সার্ভিস',
            'নড়িয়া ফায়ার সার্ভিস',
            'ডামুড্যা ফায়ার সার্ভিস',
            'শরীয়তপুর সদর ফায়ার স্টেশন',
            'সখিপুর ফায়ার সার্ভিস',
            'চন্দ্রপুর ফায়ার সার্ভিস',
            'নাগের পাড়া ফায়ার সার্ভিস',
        ];

        foreach ($stations as $name) {
            FireService::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'upazila_id' => rand(322, 327),
                    'phone' => '02'.rand(7,9).rand(1000000, 9999999),
                    'address' => $name . ' স্টেশন রোড, শরীয়তপুর',
                    'map' => 'https://maps.google.com/?q=' . urlencode($name),
                    'status' => 'active',
                ]
            );
        }
    }
}

