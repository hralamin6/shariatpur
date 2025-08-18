<?php

namespace Database\Seeders;

use App\Models\Train;
use Illuminate\Database\Seeder;

class TrainSeeder extends Seeder
{
    public function run(): void
    {
        $trains = [
            'পদ্মা এক্সপ্রেস', 'সুরমা এক্সপ্রেস', 'জননী এক্সপ্রেস', 'মেঘনা এক্সপ্রেস', 'তিতাস এক্সপ্রেস',
            'চন্দ্রিমা এক্সপ্রেস', 'সোনারগাঁও এক্সপ্রেস', 'দিগন্ত এক্সপ্রেস', 'শুভযাত্রা এক্সপ্রেস', 'বাংলা এক্সপ্রেস',
        ];

        foreach ($trains as $name) {
            Train::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'train_route_id' => rand(1, 10),
                    'phone' => '01' . rand(3, 9) . rand(10000000, 99999999),
                    'details' => 'এই ট্রেনটি আরামদায়ক এবং নিয়মিত চলাচল করে।',
                    'map_one' => null,
                    'map_two' => null,
                    'status' => 'active',
                ]
            );
        }
    }
}

