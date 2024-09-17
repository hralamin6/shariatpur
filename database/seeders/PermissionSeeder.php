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
        // Admin Dashboard Module
        $moduleAppDashboard = Module::updateOrCreate(['name' => 'Admin Dashboard']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppDashboard->id,
            'name' => 'access dashboard',
            'slug' => 'app.dashboard',
        ]);

        // Role Management Module
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

        // User Management Module
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

        // Page Management Module
        $moduleAppPage = Module::updateOrCreate(['name' => 'Page Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppPage->id,
            'name' => 'access page',
            'slug' => 'app.pages.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppPage->id,
            'name' => 'create page',
            'slug' => 'app.pages.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppPage->id,
            'name' => 'edit page',
            'slug' => 'app.pages.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppPage->id,
            'name' => 'delete page',
            'slug' => 'app.pages.delete',
        ]);

        // Category Management Module
        $moduleAppCategory = Module::updateOrCreate(['name' => 'Category Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppCategory->id,
            'name' => 'access category',
            'slug' => 'app.categories.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppCategory->id,
            'name' => 'create category',
            'slug' => 'app.categories.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppCategory->id,
            'name' => 'edit category',
            'slug' => 'app.categories.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppCategory->id,
            'name' => 'delete category',
            'slug' => 'app.categories.delete',
        ]);

        // Setting Management Module
        $moduleAppSetting = Module::updateOrCreate(['name' => 'Setting Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSetting->id,
            'name' => 'access setting',
            'slug' => 'app.settings.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSetting->id,
            'name' => 'create setting',
            'slug' => 'app.settings.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSetting->id,
            'name' => 'edit setting',
            'slug' => 'app.settings.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppSetting->id,
            'name' => 'delete setting',
            'slug' => 'app.settings.delete',
        ]);

        // Translate Management Module
        $moduleAppTranslate = Module::updateOrCreate(['name' => 'Translate Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppTranslate->id,
            'name' => 'access translate',
            'slug' => 'app.translates.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppTranslate->id,
            'name' => 'create translate',
            'slug' => 'app.translates.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppTranslate->id,
            'name' => 'edit translate',
            'slug' => 'app.translates.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleAppTranslate->id,
            'name' => 'delete translate',
            'slug' => 'app.translates.delete',
        ]);

        // UserDetails Management Module
        $moduleAppUserDetails = Module::updateOrCreate(['name' => 'UserDetails Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppUserDetails->id,
            'name' => 'access user details',
            'slug' => 'app.user_details.index',
        ]);


        // Chat Management Module
        $moduleChat = Module::updateOrCreate(['name' => 'Chat Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleChat->id,
            'name' => 'access chat',
            'slug' => 'app.chats.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleChat->id,
            'name' => 'create chat',
            'slug' => 'app.chats.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleChat->id,
            'name' => 'edit chat',
            'slug' => 'app.chats.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleChat->id,
            'name' => 'delete chat',
            'slug' => 'app.chats.delete',
        ]);

        // Notification Management Module
        $moduleNotification = Module::updateOrCreate(['name' => 'Notification Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleNotification->id,
            'name' => 'access notification',
            'slug' => 'app.notifications.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNotification->id,
            'name' => 'create notification',
            'slug' => 'app.notifications.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNotification->id,
            'name' => 'edit notification',
            'slug' => 'app.notifications.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNotification->id,
            'name' => 'delete notification',
            'slug' => 'app.notifications.delete',
        ]);

        // Profile Management Module
        $moduleProfile = Module::updateOrCreate(['name' => 'Profile Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleProfile->id,
            'name' => 'access profile',
            'slug' => 'app.profile.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleProfile->id,
            'name' => 'create profile',
            'slug' => 'app.profile.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleProfile->id,
            'name' => 'edit profile',
            'slug' => 'app.profile.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleProfile->id,
            'name' => 'delete profile',
            'slug' => 'app.profile.delete',
        ]);

        // Post Management Module
        $modulePost = Module::updateOrCreate(['name' => 'Post Management']);
        Permission::updateOrCreate([
            'module_id' => $modulePost->id,
            'name' => 'access post',
            'slug' => 'app.posts.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $modulePost->id,
            'name' => 'create post',
            'slug' => 'app.posts.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $modulePost->id,
            'name' => 'edit post',
            'slug' => 'app.posts.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $modulePost->id,
            'name' => 'delete post',
            'slug' => 'app.posts.delete',
        ]);

        // Backup Management Module
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
