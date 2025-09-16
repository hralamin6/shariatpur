<?php

declare(strict_types=1);

use Database\Seeders\{DivisionSeeder, DistrictSeeder, UpazilaSeeder, LostFoundSeeder};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the lost & found page', function () {
    $this->seed(DivisionSeeder::class);
    $this->seed(DistrictSeeder::class);
    $this->seed(UpazilaSeeder::class);
    $this->seed(LostFoundSeeder::class);

    $response = $this->get(route('web.lost_found'));

    $response->assertSuccessful();
    $response->assertSee('Lost & Found');
});

