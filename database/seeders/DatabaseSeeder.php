<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PageSeeder::class);
//        $this->call(CategorySeeder::class);

        User::updateOrCreate([
            'name' => 'admin',
            'email' => 'admin@mail.com'],[
            'email_verified_at' => now(),
            'password' => bcrypt('000000'),
            'role_id' => Role::where('slug', 'admin')->first()->id
        ]);
        User::updateOrCreate([
            'name' => 'user',
            'email' => 'user@mail.com'],[
            'email_verified_at' => now(),
            'password' => bcrypt('000000'),
            'role_id' => Role::where('slug', 'user')->first()->id
        ]);

        // Create 5 parent categories
        $parentCategories = Category::factory()->count(5)->create();

        // Create 3 child categories for each parent category
        $parentCategories->each(function ($parentCategory) {
            Category::factory()->count(3)->childCategory($parentCategory->id)->create();
        });

        // Create 10 users
        $users = User::factory()->count(10)->create();

        // For each user, create 10 posts with random child categories
        $users->each(function ($user) {
            Post::factory()->count(10)->create([
                'user_id' => $user->id,
                'category_id' => Category::whereNotNull('parent_id')->inRandomOrder()->first()->id, // Random child category
            ]);
        });
    }


}
