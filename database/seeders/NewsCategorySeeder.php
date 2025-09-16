<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first() ?? User::factory()->create();
        $categories = ['Local','National','Sports','Entertainment','Technology'];
        foreach ($categories as $name) {
            NewsCategory::updateOrCreate(
                ['name' => $name],
                ['user_id' => $user->id, 'status' => 'active']
            );
        }
    }
}

