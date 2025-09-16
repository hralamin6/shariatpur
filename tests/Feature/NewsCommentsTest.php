<?php

declare(strict_types=1);

use App\Livewire\Web\News\NewsDetailsComponent;
use App\Models\{News, NewsCategory, NewsComment, Role, User};
use Livewire\Livewire;

function makeNews(): array {
    $author = User::factory()->create();
    $cat = NewsCategory::query()->create([
        'user_id' => $author->id,
        'name' => 'Local',
        'slug' => 'local',
        'status' => 'active',
    ]);

    $news = News::query()->create([
        'user_id' => $author->id,
        'news_category_id' => $cat->id,
        'title' => 'Bridge Opening',
        'content' => 'Bridge opening details',
        'status' => 'active',
        'views' => 0,
    ]);

    return compact('author','cat','news');
}

it('redirects guest to login when adding a news comment', function () {
    extract(makeNews());

    Livewire::test(NewsDetailsComponent::class, ['slug' => $news->slug])
        ->set('commentText', 'Nice news!')
        ->call('addComment')
        ->assertRedirect(route('login'));
});

it('allows authenticated user to add a news comment', function () {
    extract(makeNews());
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(NewsDetailsComponent::class, ['slug' => $news->slug])
        ->set('commentText', 'Great read!')
        ->call('addComment')
        ->assertSet('commentText', '');

    expect(NewsComment::query()->where('news_id', $news->id)->where('user_id', $user->id)->where('body', 'Great read!')->exists())->toBeTrue();
});

