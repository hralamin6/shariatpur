<?php

namespace Database\Seeders;

use App\Models\SellCategory;
use Illuminate\Database\Seeder;

class SellCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Furniture',
            'Vehicles',
            'Appliances',
            'Clothing',
            'Books',
            'Sports',
            'Miscellaneous',
        ];

        foreach ($categories as $name) {
            SellCategory::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => 1,
                    'status' => 'active',
                ]
            );
        }
    }
}
