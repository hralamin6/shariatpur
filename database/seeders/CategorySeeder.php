<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create or update main educational categories
        $science = Category::updateOrCreate(
            ['name' => 'Science'],
            ['name' => 'Science']
        );
        $arts = Category::updateOrCreate(
            ['name' => 'Arts'],
            ['name' => 'Arts']
        );
        $literature = Category::updateOrCreate(
            ['name' => 'Literature'],
            ['name' => 'Literature']
        );

        // Create or update subcategories for Science
        Category::updateOrCreate(
            ['name' => 'Physics', 'parent_id' => $science->id],
            ['name' => 'Physics', 'parent_id' => $science->id]
        );
        Category::updateOrCreate(
            ['name' => 'Chemistry', 'parent_id' => $science->id],
            ['name' => 'Chemistry', 'parent_id' => $science->id]
        );

        // Create or update subcategories for Arts
        Category::updateOrCreate(
            ['name' => 'Painting', 'parent_id' => $arts->id],
            ['name' => 'Painting', 'parent_id' => $arts->id]
        );
        Category::updateOrCreate(
            ['name' => 'Music', 'parent_id' => $arts->id],
            ['name' => 'Music', 'parent_id' => $arts->id]
        );

        // Create or update subcategories for Literature
        Category::updateOrCreate(
            ['name' => 'Poetry', 'parent_id' => $literature->id],
            ['name' => 'Poetry', 'parent_id' => $literature->id]
        );
        Category::updateOrCreate(
            ['name' => 'Novels', 'parent_id' => $literature->id],
            ['name' => 'Novels', 'parent_id' => $literature->id]
        );
    }

}
