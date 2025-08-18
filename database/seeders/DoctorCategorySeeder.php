<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Neurology'],
            [
                'user_id' => 1,
                'name' => 'Neurology',
                'bangla_name' => 'নিউরোলজি',
                'icon' => 'bx bx-brain',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Orthopedics'],
            [
                'user_id' => 1,
                'name' => 'Orthopedics',
                'bangla_name' => 'অর্থোপেডিক্স',
                'icon' => 'bx bx-bone',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Pediatrics'],
            [
                'user_id' => 1,
                'name' => 'Pediatrics',
                'bangla_name' => 'পেডিয়াট্রিক্স',
                'icon' => 'bx bx-child',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Gynecology'],
            [
                'user_id' => 1,
                'name' => 'Gynecology',
                'bangla_name' => 'গাইনোকোলজি',
                'icon' => 'bx bx-female',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Obstetrics'],
            [
                'user_id' => 1,
                'name' => 'Obstetrics',
                'bangla_name' => 'অবসটেট্রিক্স',
                'icon' => 'bx bx-baby-carriage',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'ENT'],
            [
                'user_id' => 1,
                'name' => 'ENT',
                'bangla_name' => 'ইএনটি',
                'icon' => 'bx bx-headphone',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Ophthalmology'],
            [
                'user_id' => 1,
                'name' => 'Ophthalmology',
                'bangla_name' => 'অপথ্যালমোলজি',
                'icon' => 'bx bx-show-alt',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Psychiatry'],
            [
                'user_id' => 1,
                'name' => 'Psychiatry',
                'bangla_name' => 'সাইকিয়াট্রি',
                'icon' => 'bx bx-brain',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Urology'],
            [
                'user_id' => 1,
                'name' => 'Urology',
                'bangla_name' => 'ইউরোলজি',
                'icon' => 'bx bx-water',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Gastroenterology'],
            [
                'user_id' => 1,
                'name' => 'Gastroenterology',
                'bangla_name' => 'গ্যাস্ট্রোএন্টারোলজি',
                'icon' => 'bx bx-food-menu',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Nephrology'],
            [
                'user_id' => 1,
                'name' => 'Nephrology',
                'bangla_name' => 'নেফ্রোলজি',
                'icon' => 'bx bx-droplet',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Endocrinology'],
            [
                'user_id' => 1,
                'name' => 'Endocrinology',
                'bangla_name' => 'এন্ডোক্রাইনোলজি',
                'icon' => 'bx bx-capsule',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Pulmonology'],
            [
                'user_id' => 1,
                'name' => 'Pulmonology',
                'bangla_name' => 'পালমোনোলজি',
                'icon' => 'bx bx-wind',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Rheumatology'],
            [
                'user_id' => 1,
                'name' => 'Rheumatology',
                'bangla_name' => 'রিউমাটোলজি',
                'icon' => 'bx bx-dna',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Hematology'],
            [
                'user_id' => 1,
                'name' => 'Hematology',
                'bangla_name' => 'হেমাটোলজি',
                'icon' => 'bx bx-droplet',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Oncology'],
            [
                'user_id' => 1,
                'name' => 'Oncology',
                'bangla_name' => 'অঙ্কোলজি',
                'icon' => 'bx bx-virus',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Dentistry'],
            [
                'user_id' => 1,
                'name' => 'Dentistry',
                'bangla_name' => 'ডেন্টিস্ট্রি',
                'icon' => 'bx bx-smile',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Radiology'],
            [
                'user_id' => 1,
                'name' => 'Radiology',
                'bangla_name' => 'রেডিওলজি',
                'icon' => 'bx bx-radio',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Anesthesiology'],
            [
                'user_id' => 1,
                'name' => 'Anesthesiology',
                'bangla_name' => 'অ্যানাস্থেসিওলজি',
                'icon' => 'bx bx-injection',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Infectious Disease'],
            [
                'user_id' => 1,
                'name' => 'Infectious Disease',
                'bangla_name' => 'সংক্রামক রোগ',
                'icon' => 'bx bx-virus',
                'status' => 'active',
            ]);
        \App\Models\DoctorCategory::updateOrCreate(
            ['name' => 'Cardiology'],
            [
                'user_id' => 1,
                'name' => 'Cardiology',
                'bangla_name' => 'কার্ডিওলজি',
                'icon' => 'bx bx-heart',
                'status' => 'active',
            ]);



    }
}
