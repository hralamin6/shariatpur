<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Upazila;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BloodDonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $upazilas = Upazila::all();

        if ($upazilas->isEmpty()) {
            $this->command->warn('No upazilas found. Please seed upazilas first.');
            return;
        }

        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $donorStatuses = ['available', 'unavailable'];

        $donors = [
            [
                'name' => 'Dr. Rahman Ahmed',
                'email' => 'rahman.ahmed@example.com',
                'phone' => '01711234567',
                'blood_group' => 'A+',
                'address' => 'Shariatpur Sadar, Dhaka Division',
                'donor_details' => 'Experienced donor with 15+ donations. Available for emergency cases.',
                'total_donations' => 15,
                'last_donate_date' => now()->subDays(120),
                'donor_status' => 'available',
            ],
            [
                'name' => 'Fatima Khatun',
                'email' => 'fatima.khatun@example.com',
                'phone' => '01812345678',
                'blood_group' => 'B+',
                'address' => 'Naria, Shariatpur',
                'donor_details' => 'Regular donor, available weekends. Prefers to donate at Shariatpur Hospital.',
                'total_donations' => 8,
                'last_donate_date' => now()->subDays(95),
                'donor_status' => 'available',
            ],
            [
                'name' => 'Mohammad Karim',
                'email' => 'mohammad.karim@example.com',
                'phone' => '01913456789',
                'blood_group' => 'O+',
                'address' => 'Zanjira, Shariatpur',
                'donor_details' => 'Universal donor, always ready to help. Contact anytime for emergencies.',
                'total_donations' => 22,
                'last_donate_date' => now()->subDays(60),
                'donor_status' => 'unavailable',
            ],
            [
                'name' => 'Nasir Uddin',
                'email' => 'nasir.uddin@example.com',
                'phone' => '01714567890',
                'blood_group' => 'AB+',
                'address' => 'Gosairhat, Shariatpur',
                'donor_details' => 'New donor, first-time contributor. Available for donation.',
                'total_donations' => 2,
                'last_donate_date' => now()->subDays(150),
                'donor_status' => 'available',
            ],
            [
                'name' => 'Rashida Begum',
                'email' => 'rashida.begum@example.com',
                'phone' => '01815678901',
                'blood_group' => 'A-',
                'address' => 'Damudya, Shariatpur',
                'donor_details' => 'Regular donor with rare blood type. Contact for urgent cases.',
                'total_donations' => 12,
                'last_donate_date' => now()->subDays(100),
                'donor_status' => 'available',
            ],
            [
                'name' => 'Abdur Rahman',
                'email' => 'abdur.rahman@example.com',
                'phone' => '01916789012',
                'blood_group' => 'O-',
                'address' => 'Bhedarganj, Shariatpur',
                'donor_details' => 'Universal donor with rare O- blood type. Emergency contact available 24/7.',
                'total_donations' => 18,
                'last_donate_date' => now()->subDays(85),
                'donor_status' => 'available',
            ],
            [
                'name' => 'Salma Akter',
                'email' => 'salma.akter@example.com',
                'phone' => '01717890123',
                'blood_group' => 'B-',
                'address' => 'Shariatpur Sadar',
                'donor_details' => 'Young donor, student at local college. Available after classes.',
                'total_donations' => 4,
                'last_donate_date' => now()->subDays(140),
                'donor_status' => 'available',
            ],
            [
                'name' => 'Mizanur Rahman',
                'email' => 'mizan.rahman@example.com',
                'phone' => '01818901234',
                'blood_group' => 'AB-',
                'address' => 'Shakhipur, Shariatpur',
                'donor_details' => 'Rare blood type donor. Works at local bank, available after office hours.',
                'total_donations' => 6,
                'last_donate_date' => now()->subDays(75),
                'donor_status' => 'unavailable',
            ],
        ];

        foreach ($donors as $donorData) {
            $donorData['upazila_id'] = rand(322, 327);
            $donorData['is_blood_donor'] = true;
            $donorData['password'] = Hash::make('password123');
            $donorData['role_id'] = 2; // Assuming role_id 2 is for regular users
            $donorData['status'] = 'active';
            $donorData['email_verified_at'] = now();

            User::create($donorData);
        }

        $this->command->info('Blood donor users created successfully.');
    }
}
