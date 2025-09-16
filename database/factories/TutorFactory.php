<?php

namespace Database\Factories;

use App\Models\Tutor;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Tutor>
 */
class TutorFactory extends Factory
{
    protected $model = Tutor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'upazila_id' => Upazila::query()->inRandomOrder()->value('id') ?? 322,
            'title' => $this->faker->sentence(4),
            'type' => $this->faker->randomElement(['tutor', 'tuition']),
            'class' => $this->faker->optional()->randomElement(['Class 1-5', 'Class 6-8', 'Class 9-10', 'HSC', 'Admission']),
            'gender' => $this->faker->optional()->randomElement(['male','female','other']),
            'subject' => $this->faker->optional()->randomElement(['Math','Physics','Chemistry','Biology','English','Bangla']),
            'days_per_week' => $this->faker->optional()->numberBetween(1,7),
            'salary' => $this->faker->optional()->numberBetween(1000, 15000),
            'address' => $this->faker->streetAddress(),
            'phone' => $this->faker->numerify('01#########'),
            'map' => $this->faker->optional()->url(),
            'details' => $this->faker->optional()->sentence(15),
            'status' => 'active',
        ];
    }
}

