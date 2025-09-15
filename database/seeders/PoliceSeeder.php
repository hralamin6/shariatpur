<?php

namespace Database\Seeders;

use App\Models\Police;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PoliceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'OC Shariatpur Sadar',
                'designation' => 'Officer-in-Charge',
                'thana' => 'Shariatpur Sadar Thana',
                'address' => 'Shariatpur Sadar, Shariatpur',
                'phone' => '01710000001',
                'alt_phone' => '01719999991',
                'map' => 'https://maps.google.com/?q=Shariatpur+Sadar+Police+Station',
                'details' => 'Main police station for Shariatpur Sadar. Handles district level matters.',
                'status' => 'active',
            ],
            [
                'name' => 'OC Bhedarganj',
                'designation' => 'Officer-in-Charge',
                'thana' => 'Bhedarganj Thana',
                'address' => 'Bhedarganj, Shariatpur',
                'phone' => '01710000002',
                'alt_phone' => null,
                'map' => 'https://maps.google.com/?q=Bhedarganj+Police+Station',
                'details' => 'Bhedarganj police station serving the upazila area.',
                'status' => 'active',
            ],
            [
                'name' => 'OC Zanjira',
                'designation' => 'Officer-in-Charge',
                'thana' => 'Zanjira Thana',
                'address' => 'Zanjira, Shariatpur',
                'phone' => '01710000003',
                'alt_phone' => null,
                'map' => 'https://maps.google.com/?q=Zanjira+Police+Station',
                'details' => 'Zanjira police station.',
                'status' => 'active',
            ],
        ];

        foreach ($items as $data) {
            Police::updateOrCreate([
                'name' => $data['name'],
                'thana' => $data['thana'],
            ], array_merge($data, [
                'user_id' => rand(1, 2),
                'upazila_id' => rand(322, 327),
            ]));
        }

        // Create some randomized entries
        for ($i = 1; $i <= 8; $i++) {
            Police::create([
                'user_id' => rand(1, 2),
                'upazila_id' => rand(322, 327),
                'name' => 'Officer ' . fake()->lastName() . ' ' . $i,
                'designation' => fake()->randomElement(['Inspector', 'SI', 'ASI']),
                'thana' => fake()->city() . ' Thana',
                'address' => fake()->address(),
                'phone' => '017' . fake()->randomNumber(8, true),
                'alt_phone' => null,
                'map' => null,
                'details' => fake()->sentence(10),
                'status' => fake()->randomElement(['active', 'inactive']),
            ]);
        }

        $this->command->info('Police entries seeded successfully.');
    }
}

