<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $doctors = [
            ['name' => 'ডা. মাহমুদুল হাসান', 'qualification' => 'এমবিবিএস, এমডি (নিউরোলজি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'সিটি হাসপাতাল, ঢাকা', 'chamber_one_phone' => '01710000001'],
            ['name' => 'ডা. নুসরাত জাহান', 'qualification' => 'এমবিবিএস, এমএস (গাইনোকোলজি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'বারডেম হাসপাতাল', 'chamber_one_phone' => '01710000002'],
            ['name' => 'ডা. সাইফুল ইসলাম', 'qualification' => 'এমবিবিএস, এমএস (অর্থোপেডিক্স)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'শহীদ সোহরাওয়ার্দী মেডিকেল কলেজ', 'chamber_one_phone' => '01710000003'],
            ['name' => 'ডা. তানভীর আহমেদ', 'qualification' => 'এমবিবিএস, এফসিপিএস (শিশুরোগ)', 'current_position' => 'রেজিস্ট্রার', 'chamber_one' => 'ইবনে সিনা হাসপাতাল', 'chamber_one_phone' => '01710000004'],
            ['name' => 'ডা. আফরোজা সুলতানা', 'qualification' => 'এমবিবিএস, এমডি (কার্ডিওলজি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'জাতীয় হৃদরোগ ইনস্টিটিউট', 'chamber_one_phone' => '01710000005'],
            ['name' => 'ডা. রাশেদুল করিম', 'qualification' => 'এমবিবিএস, এমএস (ইএনটি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'ঢাকা মেডিকেল কলেজ', 'chamber_one_phone' => '01710000006'],
            ['name' => 'ডা. লুবনা হোসেন', 'qualification' => 'এমবিবিএস, এমএস (চক্ষু রোগ)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'বঙ্গবন্ধু চক্ষু হাসপাতাল', 'chamber_one_phone' => '01710000007'],
            ['name' => 'ডা. কামরুল হাসান', 'qualification' => 'এমবিবিএস, এমডি (সাইকিয়াট্রি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'মেন্টাল হেলথ সেন্টার, ঢাকা', 'chamber_one_phone' => '01710000008'],
            ['name' => 'ডা. জাহানারা বেগম', 'qualification' => 'এমবিবিএস, এমএস (ইউরোলজি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'ইবনে সিনা হাসপাতাল', 'chamber_one_phone' => '01710000009'],
            ['name' => 'ডা. শহীদুল আলম', 'qualification' => 'এমবিবিএস, এমডি (গ্যাস্ট্রোএন্টারোলজি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'বারডেম হাসপাতাল', 'chamber_one_phone' => '01710000010'],
            ['name' => 'ডা. মেহজাবিন আক্তার', 'qualification' => 'এমবিবিএস, এমডি (নেফ্রোলজি)', 'current_position' => 'রেজিস্ট্রার', 'chamber_one' => 'কিডনি ফাউন্ডেশন', 'chamber_one_phone' => '01710000011'],
            ['name' => 'ডা. হাসান মাহমুদ', 'qualification' => 'এমবিবিএস, এফসিপিএস (এন্ডোক্রাইনোলজি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'সিটি হাসপাতাল', 'chamber_one_phone' => '01710000012'],
            ['name' => 'ডা. মুনমুন রহমান', 'qualification' => 'এমবিবিএস, এমডি (পালমোনোলজি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'জাতীয় বক্ষব্যাধি ইনস্টিটিউট', 'chamber_one_phone' => '01710000013'],
            ['name' => 'ডা. আসাদুজ্জামান', 'qualification' => 'এমবিবিএস, এমএস (রিউমাটোলজি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'ঢাকা মেডিকেল কলেজ', 'chamber_one_phone' => '01710000014'],
            ['name' => 'ডা. ফারজানা ইসলাম', 'qualification' => 'এমবিবিএস, এমডি (হেমাটোলজি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'বারডেম হাসপাতাল', 'chamber_one_phone' => '01710000015'],
            ['name' => 'ডা. রুবেল আহমেদ', 'qualification' => 'এমবিবিএস, এমডি (অঙ্কোলজি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'ন্যাশনাল ক্যান্সার ইনস্টিটিউট', 'chamber_one_phone' => '01710000016'],
            ['name' => 'ডা. ফারহানা হক', 'qualification' => 'এমবিবিএস, এমডি (ডেন্টিস্ট্রি)', 'current_position' => 'ডেন্টাল সার্জন', 'chamber_one' => 'ডেন্টাল কেয়ার সেন্টার', 'chamber_one_phone' => '01710000017'],
            ['name' => 'ডা. মাহফুজা পারভীন', 'qualification' => 'এমবিবিএস, এমডি (রেডিওলজি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'ঢাকা মেডিকেল কলেজ', 'chamber_one_phone' => '01710000018'],
            ['name' => 'ডা. সেলিম হোসেন', 'qualification' => 'এমবিবিএস, এমডি (অ্যানাস্থেসিওলজি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'ইবনে সিনা হাসপাতাল', 'chamber_one_phone' => '01710000019'],
            ['name' => 'ডা. লায়লা খানম', 'qualification' => 'এমবিবিএস, এমডি (সংক্রামক রোগ)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'মেডিসিন সেন্টার, ঢাকা', 'chamber_one_phone' => '01710000020'],
            ['name' => 'ডা. রাকিবুল ইসলাম', 'qualification' => 'এমবিবিএস, এফসিপিএস (কার্ডিওলজি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'জাতীয় হৃদরোগ ইনস্টিটিউট', 'chamber_one_phone' => '01710000021'],
            ['name' => 'ডা. শারমিন সুলতানা', 'qualification' => 'এমবিবিএস, এমএস (গাইনোকোলজি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'ঢাকা মেডিকেল কলেজ', 'chamber_one_phone' => '01710000022'],
            ['name' => 'ডা. ইমরান কবির', 'qualification' => 'এমবিবিএস, এমএস (অর্থোপেডিক্স)', 'current_position' => 'রেজিস্ট্রার', 'chamber_one' => 'শহীদ সোহরাওয়ার্দী মেডিকেল কলেজ', 'chamber_one_phone' => '01710000023'],
            ['name' => 'ডা. নাদিয়া রহমান', 'qualification' => 'এমবিবিএস, এমডি (শিশুরোগ)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'ইবনে সিনা হাসপাতাল', 'chamber_one_phone' => '01710000024'],
            ['name' => 'ডা. সাদিয়া আক্তার', 'qualification' => 'এমবিবিএস, এমডি (নিউরোলজি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'বারডেম হাসপাতাল', 'chamber_one_phone' => '01710000025'],
            ['name' => 'ডা. ইকবাল মাহমুদ', 'qualification' => 'এমবিবিএস, এফসিপিএস (এন্ডোক্রাইনোলজি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'সিটি হাসপাতাল', 'chamber_one_phone' => '01710000026'],
            ['name' => 'ডা. আরিফা সুলতানা', 'qualification' => 'এমবিবিএস, এমডি (পালমোনোলজি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'জাতীয় বক্ষব্যাধি ইনস্টিটিউট', 'chamber_one_phone' => '01710000027'],
            ['name' => 'ডা. জুবায়ের হাসান', 'qualification' => 'এমবিবিএস, এমডি (সাইকিয়াট্রি)', 'current_position' => 'সহকারী অধ্যাপক', 'chamber_one' => 'ঢাকা মেডিকেল কলেজ', 'chamber_one_phone' => '01710000028'],
            ['name' => 'ডা. রেহান কবির', 'qualification' => 'এমবিবিএস, এমডি (অঙ্কোলজি)', 'current_position' => 'কনসালট্যান্ট', 'chamber_one' => 'ন্যাশনাল ক্যান্সার ইনস্টিটিউট', 'chamber_one_phone' => '01710000029'],
            ['name' => 'ডা. মিমি রহমান', 'qualification' => 'এমবিবিএস, এমডি (ডেন্টিস্ট্রি)', 'current_position' => 'ডেন্টাল সার্জন', 'chamber_one' => 'ডেন্টাল কেয়ার সেন্টার', 'chamber_one_phone' => '01710000030'],
        ];

        foreach ($doctors as $doctor) {
            Doctor::updateOrCreate(
                ['name' => $doctor['name']],
                [
                'user_id' => rand(1, 2),
                'doctor_category_id' => rand(1, 20),
                'qualification' => $doctor['qualification'],
                'current_position' => $doctor['current_position'],
                'chamber_one' => $doctor['chamber_one'],
                'chamber_two' => $doctor['chamber_two'] ?? null,
                'chamber_three' => $doctor['chamber_three'] ?? null,
                'chamber_one_phone' => $doctor['chamber_one_phone'],
                'chamber_two_phone' => $doctor['chamber_two_phone'] ?? null,
                'chamber_three_phone' => $doctor['chamber_three_phone'] ?? null,
                'status' => 'active',
            ]);
        }
    }
}
