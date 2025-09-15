<?php

namespace Database\Seeders;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure at least one user exists
        $userId = User::query()->value('id') ?? User::factory()->create()->id;

        $preset = [
            [
                'title' => 'Welcome to Our Community!',
                'content' => 'We are excited to have you here. Stay tuned for updates and events.',
                'pinned' => true,
            ],
            [
                'title' => 'New Features Released',
                'content' => 'Check out the latest features we have added to enhance your experience.',
                'pinned' => true,
            ],
            [
                'title' => 'Scheduled Maintenance',
                'content' => 'Our platform will be undergoing maintenance on Saturday from 2 AM to 4 AM UTC. Please plan accordingly.',
                'pinned' => false,
            ],
            [
                'title' => 'Community Event Next Week',
                'content' => 'We will discuss upcoming plans and collect suggestions. Everyone is welcome!',
                'pinned' => false,
            ],
        ];

        foreach ($preset as $data) {
            Notice::updateOrCreate(
                ['title' => $data['title']],
                array_merge($data, [
                    'user_id' => $userId,
                ])
            );
        }
    }
}

