<?php

declare(strict_types=1);

use App\Livewire\Web\Blog\BlogDetailsComponent;
use App\Models\{Blog, BlogCategory, Comment, User};
use Livewire\Livewire;

it('redirects guest to login when adding a comment', function () {
    $user = User::factory()->create();
    $cat = BlogCategory::query()->create([
        'user_id' => $user->id,
        'name' => 'Tech',
        'slug' => 'tech',
        'status' => 'active',
    ]);

    $blog = Blog::query()->create([
        'user_id' => $user->id,
        'blog_category_id' => $cat->id,
        'title' => 'New Gadgets',
        'content' => 'Cool gadgets this year',
        'status' => 'active',
    ]);

    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug])
        ->set('commentText', 'Nice post!')
        ->call('addComment')
        ->assertRedirect(route('login'));
});

it('allows authenticated user to add a comment', function () {
    $user = User::factory()->create();
    $cat = BlogCategory::query()->create([
        'user_id' => $user->id,
        'name' => 'News',
        'slug' => 'news',
        'status' => 'active',
    ]);

    $blog = Blog::query()->create([
        'user_id' => $user->id,
        'blog_category_id' => $cat->id,
        'title' => 'City Update',
        'content' => 'City news details',
        'status' => 'active',
    ]);

    $this->actingAs($user);

    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug])
        ->set('commentText', 'Great read!')
        ->call('addComment')
        ->assertSet('commentText', '');

    expect(Comment::query()->where('blog_id', $blog->id)->where('user_id', $user->id)->where('body', 'Great read!')->exists())->toBeTrue();
});

