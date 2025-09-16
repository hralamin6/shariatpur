<?php

namespace Database\Seeders;

use App\Models\Lawyer;
use App\Models\Upazila;
use Illuminate\Database\Seeder;

class LawyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $upaId = Upazila::query()->inRandomOrder()->value('id') ?? 1;

        $items = [
            [
                'name' => 'Adv. Rahim Uddin',
                'designation' => 'Advocate, District Bar',
                'thana' => 'Shariatpur Judge Court',
                'address' => 'Chamber Road, Shariatpur',
                'phone' => '01712000001',
                'alt_phone' => '01712999991',
                'map' => 'https://maps.google.com/?q=Shariatpur+Judge+Court',
                'details' => 'Civil and criminal lawyer with 10+ years experience.',
                'status' => 'active',
            ],
            [
                'name' => 'Adv. Salma Akter',
                'designation' => 'Advocate, Supreme Court Panel',
                'thana' => 'Bhedarganj Court Area',
                'address' => 'Bhedarganj, Shariatpur',
                'phone' => '01712000002',
                'alt_phone' => null,
                'map' => 'https://maps.google.com/?q=Bhedarganj+Court',
                'details' => 'Specializes in family and property law.',
                'status' => 'active',
            ],
            [
                'name' => 'Barrister Tanvir Hasan',
                'designation' => 'Barrister-at-Law',
                'thana' => 'Zanjira Court Area',
                'address' => 'Zanjira, Shariatpur',
                'phone' => '01712000003',
                'alt_phone' => null,
                'map' => 'https://maps.google.com/?q=Zanjira+Court',
                'details' => 'Handles corporate and tax matters.',
                'status' => 'active',
            ],
        ];

        foreach ($items as $data) {
            Lawyer::updateOrCreate([
                'name' => $data['name'],
                'thana' => $data['thana'],
            ], array_merge($data, [
                'user_id' => 1,
                'upazila_id' => $upaId,
            ]));
        }
    }
}
