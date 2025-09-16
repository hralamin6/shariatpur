<?php

namespace Database\Factories;

use App\Models\Hotline;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hotline>
 */
class HotlineFactory extends Factory
{
    protected $model = Hotline::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->unique()->company() . ' Hotline',
            'phone' => $this->faker->numerify('01#########'),
            'link' => $this->faker->optional()->url(),
            'status' => 'active',
        ];
    }
}

