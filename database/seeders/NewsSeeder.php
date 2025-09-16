<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first() ?? User::factory()->create();
        $cat = NewsCategory::query()->first() ?? NewsCategory::create([
            'user_id' => $user->id,
            'name' => 'General',
            'status' => 'active',
        ]);

        $items = [
            ['title' => 'City Council Updates', 'content' => 'Latest decisions affecting the city services...', 'is_pinned' => true],
            ['title' => 'New Park Opening', 'content' => 'A new park opens downtown with facilities...', 'is_pinned' => false],
        ];

        foreach ($items as $i) {
            News::updateOrCreate(
                ['title' => $i['title']],
                [
                    'user_id' => $user->id,
                    'news_category_id' => $cat->id,
                    'content' => $i['content'],
                    'status' => 'active',
                    'is_pinned' => (bool) $i['is_pinned'],
                    'views' => 0,
                ]
            );
        }
    }
}

