<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;

it('renders home page', function () {
    $this->get(route('web.home'))->assertSuccessful();
});

it('shows recent blogs section when blogs exist', function () {
    $user = User::factory()->create();
    $cat = BlogCategory::query()->create([
        'user_id' => $user->id,
        'name' => 'News',
        'slug' => 'news',
        'status' => 'active',
    ]);

    Blog::query()->create([
        'user_id' => $user->id,
        'blog_category_id' => $cat->id,
        'upazila_id' => null,
        'title' => 'Test Blog Title',
        'slug' => 'test-blog-title',
        'content' => 'Hello world content',
        'status' => 'active',
    ]);

    $this->get(route('web.home'))
        ->assertSuccessful()
        ->assertSee('Recent Blogs')
        ->assertSee('Test Blog Title');
});

