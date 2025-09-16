<?php

declare(strict_types=1);

use App\Livewire\Web\Blog\BlogDetailsComponent;
use App\Models\{Blog, BlogCategory, BlogLike, User};
use Livewire\Livewire;

function prepareBlog(): array {
    $author = User::factory()->create();
    $cat = BlogCategory::query()->create([
        'user_id' => $author->id,
        'name' => 'Tech',
        'slug' => 'tech',
        'status' => 'active',
    ]);

    $blog = Blog::query()->create([
        'user_id' => $author->id,
        'blog_category_id' => $cat->id,
        'title' => 'Awesome Post',
        'content' => 'Body',
        'status' => 'active',
        'views' => 0,
    ]);

    return compact('author', 'cat', 'blog');
}

it('redirects guest to login when liking', function () {
    extract(prepareBlog());

    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug])
        ->call('toggleLike')
        ->assertRedirect(route('login'));
});

it('allows user to like and unlike a blog', function () {
    extract(prepareBlog());
    $user = User::factory()->create();

    $this->actingAs($user);

    // Like
    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug])
        ->call('toggleLike');

    expect(BlogLike::query()->where('blog_id', $blog->id)->where('user_id', $user->id)->exists())->toBeTrue();

    // Unlike
    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug])
        ->call('toggleLike');

    expect(BlogLike::query()->where('blog_id', $blog->id)->where('user_id', $user->id)->exists())->toBeFalse();
});

it('records a unique view for guest using session/ip', function () {
    extract(prepareBlog());

    // First visit increments
    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug]);
    $blog->refresh();
    expect($blog->views)->toBe(1);

    // Second visit in same session should not increment
    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug]);
    $blog->refresh();
    expect($blog->views)->toBe(1);
});

it('does not increment views when switching users in the same session', function () {
    extract(prepareBlog());

    $u1 = User::factory()->create();
    $u2 = User::factory()->create();

    $this->actingAs($u1);
    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug]);
    // Switch to another user within same session
    $this->actingAs($u2);
    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug]);

    $blog->refresh();
    expect($blog->views)->toBe(1);
});
