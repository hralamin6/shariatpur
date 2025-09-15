<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\InstitutionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Shariatpur Model College',
                'type' => 'College',
                'established_at' => '1995-06-15',
                'phone' => '01720000001',
                'email' => 'info@shariatpurcollege.edu.bd',
                'website' => 'https://shariatpurcollege.example',
                'address' => 'Near College Road, Shariatpur Sadar',
                'map' => 'https://maps.google.com/?q=Shariatpur+Model+College',
                'details' => 'A well-known local college offering HSC and degree pass courses.',
                'status' => 'active',
            ],
            [
                'name' => 'Shariatpur Govt. High School',
                'type' => 'School',
                'established_at' => '1978-01-01',
                'phone' => '01720000002',
                'email' => 'contact@shariatpurhs.edu.bd',
                'website' => null,
                'address' => 'Shariatpur Sadar',
                'map' => 'https://maps.google.com/?q=Shariatpur+Govt+High+School',
                'details' => 'Secondary school with long history in the district.',
                'status' => 'active',
            ],
            [
                'name' => 'Shariatpur Nursing Institute',
                'type' => 'Institute',
                'established_at' => '2008-09-01',
                'phone' => '01720000003',
                'email' => 'admissions@nursinginstitute.org',
                'website' => 'https://nursinginstitute.example',
                'address' => 'Health Complex Road, Shariatpur',
                'map' => null,
                'details' => 'Provides diploma level nursing education and training.',
                'status' => 'active',
            ],
        ];

        foreach ($items as $data) {
            $type = InstitutionType::where('name', $data['type'])->first();
            Institution::updateOrCreate([
                'name' => $data['name'],
            ], [
                'user_id' => rand(1, 2),
                'upazila_id' => rand(322, 327),
                'institution_type_id' => $type?->id,
                'established_at' => $data['established_at'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'website' => $data['website'],
                'address' => $data['address'],
                'map' => $data['map'],
                'details' => $data['details'],
                'status' => $data['status'],
            ]);
        }

    }
}
