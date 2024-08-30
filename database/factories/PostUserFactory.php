<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostUser>
 */
class PostUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->sentence(8),
            'user_id' => rand(1, 10),
            'post_id' => rand(1, 50),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
