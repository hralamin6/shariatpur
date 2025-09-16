<?php

namespace Database\Factories;

use App\Models\ServicemanType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ServicemanType>
 */
class ServicemanTypeFactory extends Factory
{
    protected $model = ServicemanType::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->unique()->randomElement([
                'Electrician','Plumber','AC Technician','Carpenter','Painter','Mechanic','Cleaner','Gardener'
            ]),
            'status' => 'active',
        ];
    }
}

