<?php

declare(strict_types=1);

use App\Livewire\Web\News\NewsDetailsComponent;
use App\Models\{News, NewsCategory, NewsComment, Role, User};
use Livewire\Livewire;

function makeNewsWithComment(): array {
    $author = User::factory()->create();
    $cat = NewsCategory::query()->create([
        'user_id' => $author->id,
        'name' => 'General',
        'slug' => 'general',
        'status' => 'active',
    ]);

    $news = News::query()->create([
        'user_id' => $author->id,
        'news_category_id' => $cat->id,
        'title' => 'Sample News',
        'content' => 'News body',
        'status' => 'active',
        'views' => 0,
    ]);

    $commenter = User::factory()->create();
    $comment = NewsComment::query()->create([
        'news_id' => $news->id,
        'user_id' => $commenter->id,
        'body' => 'Hello',
    ]);

    return compact('author','cat','news','commenter','comment');
}

it('allows commenter to delete own news comment', function () {
    extract(makeNewsWithComment());

    $this->actingAs($commenter);

    Livewire::test(NewsDetailsComponent::class, ['slug' => $news->slug])
        ->call('deleteComment', $comment->id);

    expect(NewsComment::query()->whereKey($comment->id)->exists())->toBeFalse();
});

it('allows news owner to delete any comment on their news', function () {
    extract(makeNewsWithComment());

    $this->actingAs($author);

    Livewire::test(NewsDetailsComponent::class, ['slug' => $news->slug])
        ->call('deleteComment', $comment->id);

    expect(NewsComment::query()->whereKey($comment->id)->exists())->toBeFalse();
});

it('allows admin to delete any news comment', function () {
    extract(makeNewsWithComment());

    $admin = User::factory()->create();
    $adminRole = Role::query()->create(['name' => 'Administrator', 'slug' => 'admin']);
    $admin->role()->associate($adminRole)->save();

    $this->actingAs($admin);

    Livewire::test(NewsDetailsComponent::class, ['slug' => $news->slug])
        ->call('deleteComment', $comment->id);

    expect(NewsComment::query()->whereKey($comment->id)->exists())->toBeFalse();
});

it('prevents unauthorized user from deleting others news comments', function () {
    extract(makeNewsWithComment());

    $stranger = User::factory()->create();
    $this->actingAs($stranger);

    Livewire::test(NewsDetailsComponent::class, ['slug' => $news->slug])
        ->call('deleteComment', $comment->id)
        ->assertForbidden();

    expect(NewsComment::query()->whereKey($comment->id)->exists())->toBeTrue();
});

