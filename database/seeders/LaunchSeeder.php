<?php

namespace Database\Seeders;

use App\Models\Launch;
use Illuminate\Database\Seeder;

class LaunchSeeder extends Seeder
{
    public function run(): void
    {
        $launches = [
            'শরীয়তপুর লঞ্চ সার্ভিস', 'মেঘনা লঞ্চ', 'পদ্মা লঞ্চ', 'জননী লঞ্চ', 'সুরমা লঞ্চ',
            'তিতাস লঞ্চ', 'চন্দ্রিমা লঞ্চ', 'সোনারগাঁও লঞ্চ', 'দিগন্ত লঞ্চ', 'বাংলা লঞ্চ',
        ];

        foreach ($launches as $name) {
            Launch::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'launch_route_id' => rand(1, 6),
                    'phone' => '01' . rand(3, 9) . rand(10000000, 99999999),
                    'details' => 'এই লঞ্চটি আরামদায়ক এবং নিয়মিত চলাচল করে।',
                    'map_one' => null,
                    'map_two' => null,
                    'status' => 'active',
                ]
            );
        }
    }
}

