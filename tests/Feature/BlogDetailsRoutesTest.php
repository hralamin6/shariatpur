<?php

declare(strict_types=1);

use App\Models\{Blog, BlogCategory, User};

it('renders blog details page', function () {
    $user = User::factory()->create();
    $cat = BlogCategory::query()->create([
        'user_id' => $user->id,
        'name' => 'Travel',
        'slug' => 'travel',
        'status' => 'active',
    ]);

    $blog = Blog::query()->create([
        'user_id' => $user->id,
        'blog_category_id' => $cat->id,
        'title' => 'My First Trip',
        'slug' => 'my-first-trip',
        'content' => 'A great story about traveling.',
        'status' => 'active',
    ]);

    $this->get(route('web.blog.details', $blog->slug))
        ->assertSuccessful()
        ->assertSee($blog->title)
        ->assertSee('Related Posts');
});

it('returns 404 for missing blog details', function () {
    $this->get(route('web.blog.details', 'missing-post-slug'))
        ->assertNotFound();
});

