<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moduleAppDashboard = Module::updateOrCreate(['name' => 'Admin Dashboard']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppDashboard->id,
            'name' => 'access dashboard',
            'slug' => 'app.dashboard',
        ]);

        $moduleAppRole = Module::updateOrCreate(['name' => 'Role Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'access role',
            'slug' => 'app.roles.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'create role',
            'slug' => 'app.roles.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'edit role',
            'slug' => 'app.roles.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppRole->id,
            'name' => 'delete role',
            'slug' => 'app.roles.delete',
        ]);

        $moduleAppUser = Module::updateOrCreate(['name' => 'User Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppUser->id,
            'name' => 'access user',
            'slug' => 'app.users.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppUser->id,
            'name' => 'create user',
            'slug' => 'app.users.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppUser->id,
            'name' => 'edit user',
            'slug' => 'app.users.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppUser->id,
            'name' => 'delete user',
            'slug' => 'app.users.delete',
        ]);



        $moduleAppBackup = Module::updateOrCreate(['name' => 'Backup Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppBackup->id,
            'name' => 'access backup',
            'slug' => 'app.backups.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppBackup->id,
            'name' => 'create backup',
            'slug' => 'app.backups.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppBackup->id,
            'name' => 'download backup',
            'slug' => 'app.backups.download',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppBackup->id,
            'name' => 'delete backup',
            'slug' => 'app.backups.delete',
        ]);
    }
}
