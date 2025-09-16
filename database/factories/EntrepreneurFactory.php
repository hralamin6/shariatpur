<?php

namespace Database\Factories;

use App\Models\Entrepreneur;
use App\Models\Upazila;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Entrepreneur>
 */
class EntrepreneurFactory extends Factory
{
    protected $model = Entrepreneur::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'upazila_id' => Upazila::query()->inRandomOrder()->value('id') ?? 322,
            'name' => $this->faker->unique()->company(),
            'service' => $this->faker->randomElement(['IT Support', 'Boutique', 'Consulting', 'Agro Products', 'Trading', 'Design Studio']),
            'facebook_page' => 'https://facebook.com/'.$this->faker->userName(),
            'phone' => '01'.$this->faker->randomDigitNotNull().$this->faker->randomNumber(8, true),
            'address' => $this->faker->address(),
            'details' => $this->faker->sentence(8),
            'status' => 'active',
        ];
    }
}
