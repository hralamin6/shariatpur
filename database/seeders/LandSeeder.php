<?php

namespace Database\Seeders;

use App\Models\Land;
use App\Models\Upazila;
use Illuminate\Database\Seeder;

class LandSeeder extends Seeder
{
    public function run(): void
    {
        $upaId = Upazila::query()->inRandomOrder()->value('id') ?? 1;

        $items = [
            [
                'title' => 'Residential Plot near Sadar Upazila',
                'area' => '5 Katha',
                'price' => 1200000,
                'phone' => '01716000001',
                'address' => 'Sadar, Shariatpur',
                'map' => 'https://maps.google.com/?q=Sadar+Shariatpur',
                'details' => 'South-facing plot with proper road access.',
                'status' => 'active',
            ],
            [
                'title' => 'Commercial Land at Jajira',
                'area' => '10 Katha',
                'price' => 3500000,
                'phone' => '01716000002',
                'address' => 'Jajira, Shariatpur',
                'map' => 'https://maps.google.com/?q=Jajira+Shariatpur',
                'details' => 'Suitable for showroom/warehouse.',
                'status' => 'active',
            ],
        ];

        foreach ($items as $data) {
            Land::updateOrCreate([
                'title' => $data['title'],
            ], array_merge($data, [
                'user_id' => 1,
                'upazila_id' => $upaId,
            ]));
        }
    }
}

