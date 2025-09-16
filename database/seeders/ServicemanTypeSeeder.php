<?php

namespace Database\Seeders;

use App\Models\ServicemanType;
use Illuminate\Database\Seeder;

class ServicemanTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Electrician',
            'Plumber',
            'AC Technician',
            'Carpenter',
            'Painter',
            'Mechanic',
            'Cleaner',
            'Gardener',
        ];

        foreach ($types as $name) {
            ServicemanType::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'status' => 'active',
                ]
            );
        }
    }
}

