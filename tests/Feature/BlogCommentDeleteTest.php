<?php

declare(strict_types=1);

use App\Livewire\Web\Blog\BlogDetailsComponent;
use App\Models\{Blog, BlogCategory, Comment, Role, User};
use Livewire\Livewire;

function makeBlogWithComment(): array {
    $author = User::factory()->create();
    $cat = BlogCategory::query()->create([
        'user_id' => $author->id,
        'name' => 'General',
        'slug' => 'general',
        'status' => 'active',
    ]);

    $blog = Blog::query()->create([
        'user_id' => $author->id,
        'blog_category_id' => $cat->id,
        'title' => 'Sample Blog',
        'content' => 'Body',
        'status' => 'active',
    ]);

    $commenter = User::factory()->create();
    $comment = Comment::query()->create([
        'blog_id' => $blog->id,
        'user_id' => $commenter->id,
        'body' => 'Hello',
    ]);

    return compact('author', 'cat', 'blog', 'commenter', 'comment');
}

it('allows commenter to delete own comment', function () {
    extract(makeBlogWithComment());

    $this->actingAs($commenter);

    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug])
        ->call('deleteComment', $comment->id);

    expect(Comment::query()->whereKey($comment->id)->exists())->toBeFalse();
});

it('allows blog owner to delete any comment on their blog', function () {
    extract(makeBlogWithComment());

    $this->actingAs($author);

    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug])
        ->call('deleteComment', $comment->id);

    expect(Comment::query()->whereKey($comment->id)->exists())->toBeFalse();
});

it('allows admin to delete any comment', function () {
    extract(makeBlogWithComment());

    $admin = User::factory()->create();
    $adminRole = Role::query()->create(['name' => 'Administrator', 'slug' => 'admin']);
    $admin->role()->associate($adminRole)->save();

    $this->actingAs($admin);

    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug])
        ->call('deleteComment', $comment->id);

    expect(Comment::query()->whereKey($comment->id)->exists())->toBeFalse();
});

it('prevents unauthorized user from deleting others comments', function () {
    extract(makeBlogWithComment());

    $stranger = User::factory()->create();
    $this->actingAs($stranger);

    Livewire::test(BlogDetailsComponent::class, ['slug' => $blog->slug])
        ->call('deleteComment', $comment->id)
        ->assertForbidden();

    expect(Comment::query()->whereKey($comment->id)->exists())->toBeTrue();
});
