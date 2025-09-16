<?php

namespace Database\Seeders;

use App\Models\Entrepreneur;
use Illuminate\Database\Seeder;

class EntrepreneurSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'শরীয়তপুর আইটি সার্ভিসেস',
            'জাজিরা বুটিক হাউস',
            'ভেদরগঞ্জ কনসাল্টিং',
            'গোসাইরহাট এগ্রো',
            'নড়িয়া ট্রেডার্স',
            'ডামুড্যা ক্রিয়েটিভ স্টুডিও',
        ];

        foreach ($names as $name) {
            Entrepreneur::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => 1,
                    'upazila_id' => rand(322, 327),
                    'service' => \Illuminate\Support\Arr::random(['IT Support', 'Boutique', 'Consulting', 'Agro Products', 'Trading', 'Design Studio']),                    'facebook_page' => 'https://facebook.com/'.str_replace(' ', '', strtolower($name)),
                    'phone' => '01'.rand(3, 9).rand(10000000, 99999999),
                    'address' => $name.' এলাকায়, শরীয়তপুর',
                    'details' => 'স্থানীয় উদ্যোক্তা, সেবা প্রদান করে থাকেন।',
                    'status' => 'active',
                ]
            );
        }
    }
}
