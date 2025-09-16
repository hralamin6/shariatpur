<?php

declare(strict_types=1);

use Database\Seeders\{LaunchRouteSeeder, LaunchSeeder};

it('renders launches page with details button', function () {
    $this->seed(LaunchRouteSeeder::class);
    $this->seed(LaunchSeeder::class);

    $this->get(route('web.launch.launches'))
        ->assertSuccessful()
        ->assertSee('Details');
});

