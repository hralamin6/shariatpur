<?php

declare(strict_types=1);

use Database\Seeders\DivisionSeeder;
use Database\Seeders\DistrictSeeder;
use Database\Seeders\UpazilaSeeder;
use Database\Seeders\LawyerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the lawyers page', function () {
    // Seed minimal location data and lawyers
    $this->seed(DivisionSeeder::class);
    $this->seed(DistrictSeeder::class);
    $this->seed(UpazilaSeeder::class);
    $this->seed(LawyerSeeder::class);

    $response = $this->get('/lawyers');

    $response->assertSuccessful();
    $response->assertSee('Lawyers');
});

