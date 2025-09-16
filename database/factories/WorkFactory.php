<?php

namespace Database\Factories;

use App\Models\Upazila;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    protected $model = Work::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'upazila_id' => Upazila::query()->inRandomOrder()->value('id') ?? 322,
            'title' => $this->faker->jobTitle(),
            'institution_name' => $this->faker->company(),
            'designation' => $this->faker->jobTitle(),
            'posts_count' => $this->faker->numberBetween(0, 50),
            'educational_qualification' => $this->faker->sentence(12),
            'experience' => $this->faker->numberBetween(0, 10) . ' years',
            'salary' => $this->faker->numberBetween(15, 50) * 1000 . ' BDT',
            'email' => $this->faker->safeEmail(),
            'phone' => '01' . $this->faker->randomDigitNotNull() . $this->faker->randomNumber(8, true),
            'last_date' => $this->faker->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'address' => $this->faker->address(),
            'application_link' => 'https://example.com/apply',
            'details' => $this->faker->paragraph(2),
            'status' => 'active',
        ];
    }
}

