<?php

namespace Database\Seeders;

use App\Models\Tutor;
use Illuminate\Database\Seeder;

class TutorSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Experienced Math Tutor for Class 8',
                'type' => 'tutor',
                'class' => 'Class 8',
                'gender' => 'male',
                'subject' => 'Mathematics',
                'days_per_week' => 3,
                'salary' => 5000,
            ],
            [
                'title' => 'Need Physics Tuition for HSC',
                'type' => 'tuition',
                'class' => 'HSC',
                'gender' => 'female',
                'subject' => 'Physics',
                'days_per_week' => 4,
                'salary' => 8000,
            ],
            [
                'title' => 'English Tutor Needed (Class 6-8)',
                'type' => 'tuition',
                'class' => 'Class 6-8',
                'gender' => 'other',
                'subject' => 'English',
                'days_per_week' => 3,
                'salary' => 4500,
            ],
        ];

        foreach ($items as $data) {
            Tutor::updateOrCreate(
                ['title' => $data['title']],
                [
                    'user_id' => 1,
                    'upazila_id' => rand(322, 327),
                    'type' => $data['type'],
                    'class' => $data['class'],
                    'gender' => $data['gender'],
                    'subject' => $data['subject'],
                    'days_per_week' => $data['days_per_week'],
                    'salary' => $data['salary'],
                    'address' => 'Shariatpur Sadar',
                    'phone' => '01'.rand(3,9).rand(10000000, 99999999),
                    'map' => null,
                    'details' => 'Flexible timing available. Prefer nearby students.',
                    'status' => 'active',
                ]
            );
        }
    }
}

