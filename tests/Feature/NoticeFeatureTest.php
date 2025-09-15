<?php

declare(strict_types=1);

use App\Models\Notice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows notices page', function () {
    $user = User::factory()->create();
    Notice::factory()->for($user)->create([
        'status' => 'published',
        'title' => 'Test Notice',
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get(route('web.notices'));
    $response->assertSuccessful();
});

it('home shows notices link', function () {
    $response = $this->get(route('web.home'));
    $response->assertSuccessful();
    $response->assertSee('Notices', escape: false);
});

