<?php

namespace Database\Seeders;

use App\Models\LaunchRoute;
use Illuminate\Database\Seeder;

class LaunchRouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            'ঢাকা ⇄ শরীয়তপুর (লঞ্চ)',
            'শরীয়তপুর ⇄ বরিশাল (লঞ্চ)',
            'শরীয়তপুর ⇄ চাঁদপুর (লঞ্চ)',
            'শরীয়তপুর ⇄ নারায়ণগঞ্জ (লঞ্চ)',
            'শরীয়তপুর ⇄ খুলনা (লঞ্চ)',
            'শরীয়তপুর ⇄ ভোলা (লঞ্চ)',
        ];

        foreach ($routes as $name) {
            LaunchRoute::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'status' => 'active',
                ]
            );
        }
    }
}

