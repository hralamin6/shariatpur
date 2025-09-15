<?php

namespace Database\Seeders;

use App\Models\ElectricityOffice;
use Illuminate\Database\Seeder;

class ElectricityOfficeSeeder extends Seeder
{
    public function run(): void
    {
        $offices = [
            'শরীয়তপুর বিদ্যুৎ অফিস',
            'শরীয়তপুর পল্লী বিদ্যুৎ সমিতি-১',
            'ভেদরগঞ্জ বিদ্যুৎ অফিস',
            'গোসাইরহাট বিদ্যুৎ অফিস',
            'জাজিরা বিদ্যুৎ অফিস',
            'নড়িয়া বিদ্যুৎ অফিস',
            'ডামুড্যা বিদ্যুৎ অফিস',
            'সখিপুর বিদ্যুৎ অফিস',
        ];

        foreach ($offices as $name) {
            ElectricityOffice::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => 1,
                    'upazila_id' => rand(322, 327),
                    'phone' => '02'.rand(7, 9).rand(1000000, 9999999),
                    'address' => $name.' অফিস রোড, শরীয়তপুর',
                    'map' => 'https://maps.google.com/?q='.urlencode($name),
                    'status' => 'active',
                ]
            );
        }
    }
}
