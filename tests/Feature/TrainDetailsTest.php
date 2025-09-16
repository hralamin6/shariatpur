<?php

declare(strict_types=1);

use Database\Seeders\{TrainRouteSeeder, TrainSeeder};

it('renders trains page with details button', function () {
    $this->seed(TrainRouteSeeder::class);
    $this->seed(TrainSeeder::class);

    $this->get(route('web.train.trains'))
        ->assertSuccessful()
        ->assertSee('Details');
});

