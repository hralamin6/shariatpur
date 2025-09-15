<?php

namespace Database\Seeders;

use App\Models\DiagnosticCenter;
use Illuminate\Database\Seeder;

class DiagnosticCenterSeeder extends Seeder
{
    public function run(): void
    {
        $centers = [
            'Popular Diagnostic Center',
            'Ibn Sina Diagnostic & Imaging Center',
            'LabAid Diagnostic Center',
            'City Diagnostic Center',
            'Medinova Diagnostic Center',
            'Square Diagnostic Center',
            'Banani Diagnostic Center',
            'Green Life Diagnostic',
            'United Diagnostic Center',
            'Comfort Diagnostic Center',
        ];

        foreach ($centers as $name) {
            DiagnosticCenter::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'upazila_id' => rand(322, 327),
                    'phone' => '01'.rand(3, 9).rand(10000000, 99999999),
                    'address' => $name.', Shariatpur',
                    'map' => 'https://maps.google.com/?q='.urlencode($name.' Shariatpur'),
                    'status' => 'active',
                ]
            );
        }
    }
}
