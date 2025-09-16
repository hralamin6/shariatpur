<?php

declare(strict_types=1);

use App\Models\{News, NewsCategory, User};

it('renders news details page', function () {
    $user = User::factory()->create();
    $cat = NewsCategory::query()->create([
        'user_id' => $user->id,
        'name' => 'Local',
        'slug' => 'local',
        'status' => 'active',
    ]);

    $news = News::query()->create([
        'user_id' => $user->id,
        'news_category_id' => $cat->id,
        'title' => 'Town Hall Meeting',
        'slug' => 'town-hall-meeting',
        'content' => 'Details about the meeting',
        'status' => 'active',
    ]);

    $this->get(route('web.news.details', $news->slug))
        ->assertSuccessful()
        ->assertSee($news->title);
});

it('returns 404 for missing news details', function () {
    $this->get(route('web.news.details', 'missing-news-slug'))->assertNotFound();
});

