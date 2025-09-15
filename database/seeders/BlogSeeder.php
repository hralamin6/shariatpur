<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = BlogCategory::query()->inRandomOrder()->value('id') ?? null;
        if (!$categoryId) {
            $categoryId = BlogCategory::create([
                'user_id' => 1,
                'name' => 'General',
                'status' => 'active',
            ])->id;
        }

        $blogs = [
            ['title' => 'The Future of Technology in Bangladesh'],
            ['title' => 'Exploring the Sundarbans: A Travel Guide'],
            ['title' => 'Delicious Bengali Recipes to Try at Home'],
            ['title' => 'A Guide to a Healthy Lifestyle'],
            ['title' => 'The Rise of E-commerce in Dhaka'],
        ];

        foreach ($blogs as $row) {
            Blog::updateOrCreate(
                ['title' => $row['title']],
                [
                    'user_id' => rand(1, 2),
                    'blog_category_id' => $categoryId,
                    'upazila_id' => rand(322, 327),
                    'content' => 'This is a sample blog post content. The content is long enough to be a blog post. This is a sample blog post content. The content is long enough to be a blog post. This is a sample blog post content. The content is long enough to be a blog post.',
                    'status' => 'active',
                ]
            );
        }
    }
}

