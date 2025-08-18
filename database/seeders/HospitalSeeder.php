<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = [
            'ঢাকা মেডিকেল কলেজ হাসপাতাল',
            'সোহরাওয়ার্দী মেডিকেল কলেজ হাসপাতাল',
            'মুগদা জেনারেল হাসপাতাল',
            'জাতীয় হৃদরোগ ইনস্টিটিউট',
            'শিশু হাসপাতাল ও ইনস্টিটিউট',
            'জাতীয় ক্যান্সার গবেষণা ইনস্টিটিউট',
            'বারডেম জেনারেল হাসপাতাল',
            'বঙ্গবন্ধু মেডিকেল বিশ্ববিদ্যালয়',
            'শেখ হাসিনা জাতীয় বার্ন ইনস্টিটিউট',
            'সেন্ট্রাল হাসপাতাল',
            'ল্যাবএইড হাসপাতাল',
            'স্কয়ার হাসপাতাল',
            'ইবনে সিনা হাসপাতাল',
            'পপুলার মেডিকেল কলেজ হাসপাতাল',
            'আনোয়ার খান মডার্ন হাসপাতাল',
            'আল রাজি হাসপাতাল',
            'গ্রীন লাইফ মেডিকেল কলেজ হাসপাতাল',
            'শমরিতা হাসপাতাল',
            'ইউনাইটেড হাসপাতাল',
            'এভারকেয়ার হাসপাতাল',
        ];

        foreach ($hospitals as $name) {
            Hospital::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'upazila_id' => rand(322, 327),
                    'phone' => '01'.rand(3,9).rand(10000000, 99999999),
                    'address' => $name . ' রোড, ঢাকা',
                    'map' => 'https://maps.google.com/?q=' . urlencode($name),
                    'status' => 'active',
                ]
            );
        }
    }
}
