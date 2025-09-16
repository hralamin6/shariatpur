<?php

namespace Database\Seeders;

use App\Models\Work;
use Illuminate\Database\Seeder;

class WorkSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'title' => 'Office Assistant (Contract)',
                'institution_name' => 'Shariatpur Municipality',
                'designation' => 'Office Assistant',
            ],
            [
                'title' => 'Junior IT Support',
                'institution_name' => 'Local IT Firm',
                'designation' => 'IT Support',
            ],
            [
                'title' => 'Health Worker',
                'institution_name' => 'Community Clinic',
                'designation' => 'Field Worker',
            ],
        ];

        foreach ($entries as $e) {
            Work::updateOrCreate(
                ['title' => $e['title'], 'institution_name' => $e['institution_name'] ?? null],
                [
                    'user_id' => 1,
                    'upazila_id' => rand(322, 327),
                    'designation' => $e['designation'] ?? null,
                    'posts_count' => rand(1, 10),
                    'educational_qualification' => 'Minimum HSC or equivalent.',
                    'experience' => rand(0, 5) . ' years',
                    'salary' => rand(15, 30) * 1000 . ' BDT',
                    'email' => 'hr@example.com',
                    'phone' => '01' . rand(3, 9) . rand(10000000, 99999999),
                    'last_date' => now()->addDays(rand(7, 30))->format('Y-m-d'),
                    'address' => 'Shariatpur',
                    'application_link' => 'https://example.com/apply',
                    'details' => 'Apply with CV and necessary documents.',
                    'status' => 'active',
                ]
            );
        }
    }
}

