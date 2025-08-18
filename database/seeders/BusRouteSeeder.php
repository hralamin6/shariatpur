<?php

namespace Database\Seeders;

use App\Models\BusRoute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            'শরীয়তপুর থেকে ঢাকা',
            'গোসাইরহাট থেকে শরীয়তপুর',
            'ভেদরগঞ্জ থেকে ঢাকা',
            'জাজিরা থেকে ঢাকা',
            'নড়িয়া থেকে শরীয়তপুর',
            'ডামুড্যা থেকে ঢাকা',
            'শরীয়তপুর থেকে চট্টগ্রাম',
            'শরীয়তপুর থেকে নারায়ণগঞ্জ',
            'শরীয়তপুর থেকে খুলনা',
            'শরীয়তপুর থেকে রাজশাহী',
            'শরীয়তপুর থেকে সিলেট',
            'শরীয়তপুর থেকে বরিশাল',
            'শরীয়তপুর থেকে কুমিল্লা',
            'শরীয়তপুর থেকে মাদারীপুর',
            'শরীয়তপুর থেকে ফরিদপুর',
            'শরীয়তপুর থেকে গোপালগঞ্জ',
            'শরীয়তপুর থেকে মেঘনা ঘাট',
            'শরীয়তপুর থেকে মাওয়া',
            'শরীয়তপুর থেকে ফেনী',
            'শরীয়তপুর থেকে কক্সবাজার',
        ];

        foreach ($routes as $name) {
            BusRoute::updateOrCreate(
                ['name' => $name],
                [
                    'user_id' => rand(1, 2),
                    'status' => 'active',
                ]
            );
        }
    }
}
