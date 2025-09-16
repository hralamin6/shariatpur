<?php

namespace Database\Seeders;

use App\Models\Serviceman;
use App\Models\ServicemanType;
use Illuminate\Database\Seeder;

class ServicemanSeeder extends Seeder
{
    public function run(): void
    {
        $types = ServicemanType::pluck('id')->all();
        if (empty($types)) {
            $this->call(ServicemanTypeSeeder::class);
            $types = ServicemanType::pluck('id')->all();
        }

        $names = [
            'রফিক ইলেকট্রিশিয়ান',
            'করিম প্লাম্বার',
            'সজীব এসি টেকনিশিয়ান',
            'হাসান কার্পেন্টার',
            'ইমরান ক্লিনার',
            'মোস্তাফিজ মেকানিক',
        ];

        foreach ($names as $name) {
            Serviceman::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => 1,
                    'serviceman_type_id' => $types[array_rand($types)],
                    'upazila_id' => rand(322, 327),
                    'service_title' => 'Experienced professional',
                    'experience_years' => rand(1, 15),
                    'phone' => '01'.rand(3,9).rand(10000000, 99999999),
                    'address' => 'Shariatpur',
                    'details' => 'বিশ্বস্ত সার্ভিসম্যান, সময়মতো কাজ সম্পন্ন করেন।',
                    'status' => 'active',
                ]
            );
        }
    }
}

