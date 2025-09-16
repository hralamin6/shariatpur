<?php

namespace Database\Seeders;

use App\Models\BeautyParlor;
use Illuminate\Database\Seeder;

class BeautyParlorSeeder extends Seeder
{
    public function run(): void
    {
        $parlors = [
            'শরীয়তপুর গ্ল্যামার সেলুন',
            'জাজিরা বিউটি কর্নার',
            'ভেদরগঞ্জ স্টাইল হাউস',
            'গোসাইরহাট পার্লার',
            'নড়িয়া স্পা & সেলুন',
            'ডামুড্যা বিউটি লাউঞ্জ',
        ];

        foreach ($parlors as $name) {
            BeautyParlor::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => 1,
                    'upazila_id' => rand(322, 327),
                    'phone' => '01'.rand(3,9).rand(10000000, 99999999),
                    'address' => $name . ' এলাকায়, শরীয়তপুর',
                    'map' => 'https://maps.google.com/?q=' . urlencode($name),
                    'details' => 'সেবা: হেয়ার কাট, ফেসিয়াল, মেকওভার, স্পা।',
                    'status' => 'active',
                ]
            );
        }
    }
}

