<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = [
            'মেঘনা পরিবহন',
            'শরীয়তপুর এক্সপ্রেস',
            'গোসাইরহাট পরিবহন',
            'ভেদরগঞ্জ এক্সপ্রেস',
            'জাজিরা ট্রান্সপোর্ট',
            'নড়িয়া পরিবহন',
            'ডামুড্যা ট্রান্সপোর্ট',
            'ঢাকা শরীয়তপুর এক্সপ্রেস',
            'চন্দ্রিমা পরিবহন',
            'সুরমা ট্রান্সপোর্ট',
            'সোনারগাঁও এক্সপ্রেস',
            'তিতাস পরিবহন',
            'পদ্মা ট্রান্সপোর্ট',
            'মেঘনা এক্সপ্রেস',
            'আশা পরিবহন',
            'বিশ্বসাথী ট্রান্সপোর্ট',
            'শুভযাত্রা এক্সপ্রেস',
            'শরীয়তপুর লাক্সারি',
            'সাউথবেঙ্গল এক্সপ্রেস',
            'নগর পরিবহন',
            'দিগন্ত পরিবহন',
            'জননী ট্রান্সপোর্ট',
            'শান্তি পরিবহন',
            'উত্তরা এক্সপ্রেস',
            'কুসুম পরিবহন',
            'সোনালী ট্রান্সপোর্ট',
            'মুক্তি পরিবহন',
            'জনপথ এক্সপ্রেস',
            'বাংলা ট্রান্সপোর্ট',
            'আলোক পরিবহন',
        ];

        foreach ($buses as $name) {
            Bus::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'bus_route_id' => rand(1, 10),
                    'phone' => '01' . rand(3, 9) . rand(10000000, 99999999),
                    'details' => 'এই বাসটি আরামদায়ক এবং নিয়মিত চলাচল করে।',
                    'map_one' => null,
                    'map_two' => null,
                    'status' => 'active',
                ]
            );
        }
    }
}
