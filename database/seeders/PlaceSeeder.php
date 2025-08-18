<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $places = [
            'শরীয়তপুর পৌর পার্ক',
            'পদ্মা সেতু ভিউ পয়েন্ট',
            'জাজিরা বাজার',
            'ভেদরগঞ্জ মুক্তমঞ্চ',
            'গোসাইরহাট ঘাট',
            'নড়িয়া শহীদ মিনার',
            'ডামুড্যা স্টেডিয়াম',
            'শরীয়তপুর কালেক্টরেট',
            'শরীয়তপুর লাইব্রেরি',
            'শরীয়তপুর মডেল মসজিদ',
        ];

        foreach ($places as $name) {
            Place::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'upazila_id' => rand(322, 327),
                    'phone' => '01'.rand(3,9).rand(10000000, 99999999),
                    'address' => $name . ' এলাকায়, শরীয়তপুর',
                    'map' => 'https://maps.google.com/?q=' . urlencode($name),
                    'details' => 'এটি শরীয়তপুর জেলার একটি উল্লেখযোগ্য স্থান।',
                    'status' => 'active',
                ]
            );
        }
    }
}

