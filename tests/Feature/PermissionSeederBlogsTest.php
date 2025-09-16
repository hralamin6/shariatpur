<?php

declare(strict_types=1);

use Database\Seeders\PermissionSeeder;
use App\Models\Permission;

it('seeds blog category permissions', function () {
    // Run the seeder
    (new PermissionSeeder())->run();

    expect(Permission::query()->where('slug', 'app.blog_categories.index')->exists())->toBeTrue();
    expect(Permission::query()->where('slug', 'app.blog_categories.create')->exists())->toBeTrue();
    expect(Permission::query()->where('slug', 'app.blog_categories.edit')->exists())->toBeTrue();
    expect(Permission::query()->where('slug', 'app.blog_categories.delete')->exists())->toBeTrue();
});

it('seeds blog permissions', function () {
    // Run the seeder
    (new PermissionSeeder())->run();

    expect(Permission::query()->where('slug', 'app.blogs.index')->exists())->toBeTrue();
    expect(Permission::query()->where('slug', 'app.blogs.create')->exists())->toBeTrue();
    expect(Permission::query()->where('slug', 'app.blogs.edit')->exists())->toBeTrue();
    expect(Permission::query()->where('slug', 'app.blogs.delete')->exists())->toBeTrue();
});

