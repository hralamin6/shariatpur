<?php

namespace Database\Factories;

use App\Models\LostFound;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\LostFound>
 */
class LostFoundFactory extends Factory
{
    protected $model = LostFound::class;

    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? 1,
            'upazila_id' => Upazila::query()->inRandomOrder()->value('id') ?? 322,
            'title' => $this->faker->randomElement(['Lost Wallet', 'Found Phone', 'Lost ID Card', 'Found Keys']),
            'type' => $this->faker->randomElement(['lost', 'found']),
            'item' => $this->faker->randomElement(['Wallet', 'Phone', 'ID Card', 'Keys']),
            'date' => $this->faker->optional()->date(),
            'address' => $this->faker->address(),
            'phone' => '01' . $this->faker->randomNumber(9, true),
            'map' => 'https://maps.google.com/?q=' . urlencode($this->faker->streetAddress()),
            'details' => $this->faker->sentence(10),
            'status' => 'active',
        ];
    }
}

