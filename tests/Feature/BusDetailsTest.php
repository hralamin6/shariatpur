<?php

declare(strict_types=1);

use Database\Seeders\{BusRouteSeeder, BusSeeder};

it('renders buses page with details button', function () {
    // Ensure required data exists
    $this->seed(BusRouteSeeder::class);
    $this->seed(BusSeeder::class);

    $this->get(route('web.bus.buses'))
        ->assertSuccessful()
        ->assertSee('Details');
});

