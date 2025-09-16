<?php

namespace Database\Factories;

use App\Models\Land;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Land>
 */
class LandFactory extends Factory
{
    protected $model = Land::class;

    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? 1,
            'upazila_id' => Upazila::query()->inRandomOrder()->value('id') ?? 322,
            'title' => $this->faker->randomElement(['Residential Plot', 'Commercial Plot', 'Farmland']),
            'area' => $this->faker->randomElement(['3 Katha', '5 Katha', '10 Katha', '2000 sq ft']),
            'price' => $this->faker->numberBetween(200000, 5000000),
            'phone' => '01' . $this->faker->randomNumber(9, true),
            'address' => $this->faker->address(),
            'map' => 'https://maps.google.com/?q=' . urlencode($this->faker->streetAddress()),
            'details' => $this->faker->sentence(12),
            'status' => 'active',
        ];
    }
}

