<?php

namespace Database\Seeders;

use App\Models\Hotline;
use Illuminate\Database\Seeder;

class HotlineSeeder extends Seeder
{
    public function run(): void
    {
        $hotlines = [
            ['title' => 'National Emergency Service', 'phone' => '999', 'link' => 'https://www.police.gov.bd/'],
            ['title' => 'Fire Service & Civil Defense', 'phone' => '16163', 'link' => 'https://fireservice.gov.bd/'],
            ['title' => 'Health Hotline', 'phone' => '16263', 'link' => 'https://dghs.gov.bd/'],
            ['title' => 'Women & Children Helpline', 'phone' => '109', 'link' => null],
        ];

        foreach ($hotlines as $h) {
            Hotline::updateOrCreate(
                ['title' => $h['title']],
                [
                    'user_id' => 1,
                    'phone' => $h['phone'] ?? null,
                    'link' => $h['link'] ?? null,
                    'status' => 'active',
                ]
            );
        }
    }
}

