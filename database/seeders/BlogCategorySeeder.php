<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Technology',
            'Travel',
            'Food',
            'Lifestyle',
            'Fashion',
            'Business',
            'Health',
            'Education',
        ];

        foreach ($categories as $name) {
            BlogCategory::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'status' => 'active',
                ]
            );
        }
    }
}

