<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Psy\Util\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create or get a demo user
        $user = User::first() ?? User::factory()->create();

        // Create or get a demo category
        $category = Category::firstOrCreate(
            ['name' => 'General'], // Condition to check if category exists
            ['slug' => Str::slug('General')] // Values to insert if it doesn't exist
        );

        // Create or update the first blog post
        Post::updateOrCreate(
            ['slug' => Str::slug('First Blog Post')], // Condition to check if the blog exists by slug
            [
                'title' => 'First Blog Post',
                'content' => 'This is the content of the first blog post.',
                'excerpt' => 'This is the excerpt of the first blog post.',
                'category_id' => $category->id,
                'tags' => json_encode(['Laravel', 'PHP', 'Web Development']),
                'status' => 'published',
                'user_id' => $user->id,
                'meta_title' => 'First Blog Post Meta Title',
                'meta_description' => 'This is the meta description for the first blog post.',
                'published_at' => now(),
            ]
        );

        // Create or update the second blog post
        Post::updateOrCreate(
            ['slug' => Str::slug('Second Blog Post')], // Condition to check if the blog exists by slug
            [
                'title' => 'Second Blog Post',
                'content' => 'This is the content of the second blog post.',
                'excerpt' => 'This is the excerpt of the second blog post.',
                'category_id' => $category->id,
                'tags' => json_encode(['Laravel', 'VueJS', 'JavaScript']),
                'status' => 'draft',
                'user_id' => $user->id,
                'meta_title' => 'Second Blog Post Meta Title',
                'meta_description' => 'This is the meta description for the second blog post.',
                'published_at' => null,  // Not published yet
            ]
        );
    }

}
