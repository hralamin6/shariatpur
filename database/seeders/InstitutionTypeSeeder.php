<?php

namespace Database\Seeders;

use App\Models\InstitutionType;
use Illuminate\Database\Seeder;

class InstitutionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'School',
            'College',
            'University',
            'NGO',
            'Institute',
        ];

        foreach ($types as $name) {
            InstitutionType::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => 1,
                    'status' => 'active',
                ]
            );
        }
    }
}

