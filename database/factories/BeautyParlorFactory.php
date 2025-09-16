<?php

namespace Database\Factories;

use App\Models\BeautyParlor;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\BeautyParlor>
 */
class BeautyParlorFactory extends Factory
{
    protected $model = BeautyParlor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'upazila_id' => Upazila::query()->inRandomOrder()->value('id') ?? 322,
            'name' => $this->faker->unique()->company() . ' Beauty Parlor',
            'phone' => $this->faker->numerify('01#########'),
            'address' => $this->faker->streetAddress(),
            'map' => $this->faker->optional()->url(),
            'details' => $this->faker->optional()->sentence(12),
            'status' => 'active',
        ];
    }
}

