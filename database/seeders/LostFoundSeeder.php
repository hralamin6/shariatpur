<?php

namespace Database\Seeders;

use App\Models\LostFound;
use App\Models\Upazila;
use Illuminate\Database\Seeder;

class LostFoundSeeder extends Seeder
{
    public function run(): void
    {
        $upaId = Upazila::query()->inRandomOrder()->value('id') ?? 1;

        $items = [
            [
                'title' => 'Lost Wallet near Sadar Market',
                'type' => 'lost',
                'item' => 'Wallet',
                'date' => now()->subDays(2)->toDateString(),
                'address' => 'Sadar Market, Shariatpur',
                'phone' => '01715000001',
                'map' => 'https://maps.google.com/?q=Sadar+Market+Shariatpur',
                'details' => 'Black leather wallet with ID and cash.',
                'status' => 'active',
            ],
            [
                'title' => 'Found Smartphone in Bus Stand',
                'type' => 'found',
                'item' => 'Phone',
                'date' => now()->subDays(1)->toDateString(),
                'address' => 'Bus Stand, Shariatpur',
                'phone' => '01715000002',
                'map' => 'https://maps.google.com/?q=Shariatpur+Bus+Stand',
                'details' => 'Android phone with black case.',
                'status' => 'active',
            ],
        ];

        foreach ($items as $data) {
            LostFound::updateOrCreate([
                'title' => $data['title'],
                'type' => $data['type'],
            ], array_merge($data, [
                'user_id' => 1,
                'upazila_id' => $upaId,
            ]));
        }
    }
}

