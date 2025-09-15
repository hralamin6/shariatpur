<?php

namespace Database\Seeders;

use App\Models\Sell;
use App\Models\SellCategory;
use Illuminate\Database\Seeder;

class SellSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = SellCategory::query()->inRandomOrder()->value('id') ?? null;
        if (! $categoryId) {
            $categoryId = SellCategory::create([
                'user_id' => 1,
                'name' => 'Electronics',
                'status' => 'active',
            ])->id;
        }

        $items = [
            ['name' => 'Used Bicycle'],
            ['name' => 'Smartphone - Like New'],
            ['name' => 'Dining Table Set'],
            ['name' => 'Laptop Old Model'],
            ['name' => 'Microwave Oven'],
        ];

        foreach ($items as $row) {
            Sell::updateOrCreate(
                ['name' => $row['name']],
                [
                    'user_id' => 1,
                    'sell_category_id' => $categoryId,
                    'upazila_id' => rand(322, 327),
                    'phone' => '01'.rand(3, 9).rand(10000000, 99999999),
                    'details' => 'Item available for sale. Contact for details.',
                    'map_one' => null,
                    'address' => 'Common area, Shariatpur',
                    'price' => rand(500, 50000),
                    'type' => rand(0, 1) ? 'new' : 'old',
                    'status' => 'active',
                ]
            );
        }
    }
}
