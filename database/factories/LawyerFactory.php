<?php

namespace Database\Factories;

use App\Models\Lawyer;
use App\Models\User;
use App\Models\Upazila;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Lawyer>
 */
class LawyerFactory extends Factory
{
    protected $model = Lawyer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? 1,
            'upazila_id' => Upazila::query()->inRandomOrder()->value('id') ?? 322,
            'name' => $this->faker->name(),
            'designation' => $this->faker->randomElement(['Advocate', 'Senior Advocate', 'Barrister-at-Law']),
            'thana' => $this->faker->randomElement(['Judge Court', 'Chamber']),
            'address' => $this->faker->address(),
            'map' => 'https://maps.google.com/?q=' . urlencode($this->faker->streetAddress()),
            'phone' => '01' . $this->faker->randomNumber(9, true),
            'alt_phone' => $this->faker->boolean(40) ? '01' . $this->faker->randomNumber(9, true) : null,
            'details' => $this->faker->sentence(10),
            'status' => 'active',
        ];
    }
}

