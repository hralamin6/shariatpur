<?php

namespace Database\Seeders;

use App\Models\TrainRoute;
use Illuminate\Database\Seeder;

class TrainRouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            'ঢাকা ⇄ শরীয়তপুর',
            'শরীয়তপুর ⇄ রাজশাহী',
            'শরীয়তপুর ⇄ খুলনা',
            'শরীয়তপুর ⇄ চট্টগ্রাম',
            'শরীয়তপুর ⇄ সিলেট',
            'শরীয়তপুর ⇄ কুমিল্লা',
            'শরীয়তপুর ⇄ বরিশাল',
            'শরীয়তপুর ⇄ ময়মনসিংহ',
            'শরীয়তপুর ⇄ রংপুর',
            'শরীয়তপুর ⇄ কক্সবাজার',
        ];

        foreach ($routes as $name) {
            TrainRoute::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'status' => 'active',
                ]
            );
        }
    }
}

