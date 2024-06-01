<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);

        User::updateOrCreate([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('000000'),
            'role_id' => Role::where('slug', 'admin')->first()->id,
        ]);
        User::updateOrCreate([
            'name' => 'user',
            'email' => 'user@mail.com',
            'password' => bcrypt('000000'),
            'role_id' => Role::where('slug', 'user')->first()->id,
        ]);
    }
}
