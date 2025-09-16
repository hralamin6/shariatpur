<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'upazila_id' => Upazila::query()->inRandomOrder()->value('id') ?? 322,
            'name' => $this->faker->unique()->company() . ' Restaurant',
            'phone' => $this->faker->numerify('01#########'),
            'address' => $this->faker->streetAddress(),
            'map' => $this->faker->optional()->url(),
            'details' => $this->faker->optional()->sentence(12),
            'status' => 'active',
        ];
    }
}

