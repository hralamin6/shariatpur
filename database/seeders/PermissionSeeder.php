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

        // Doctor Category Management Module
        $moduleDoctorCategory = Module::updateOrCreate(['name' => 'Doctor Category Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleDoctorCategory->id,
            'name' => 'access doctor category',
            'slug' => 'app.doctor_categories.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleDoctorCategory->id,
            'name' => 'create doctor category',
            'slug' => 'app.doctor_categories.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleDoctorCategory->id,
            'name' => 'edit doctor category',
            'slug' => 'app.doctor_categories.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleDoctorCategory->id,
            'name' => 'delete doctor category',
            'slug' => 'app.doctor_categories.delete',
        ]);

        // Doctor Management Module
        $moduleDoctor = Module::updateOrCreate(['name' => 'Doctor Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleDoctor->id,
            'name' => 'access doctor',
            'slug' => 'app.doctors.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleDoctor->id,
            'name' => 'create doctor',
            'slug' => 'app.doctors.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleDoctor->id,
            'name' => 'edit doctor',
            'slug' => 'app.doctors.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleDoctor->id,
            'name' => 'delete doctor',
            'slug' => 'app.doctors.delete',
        ]);

        // Hospital Management Module
        $moduleHospital = Module::updateOrCreate(['name' => 'Hospital Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleHospital->id,
            'name' => 'access hospital',
            'slug' => 'app.hospitals.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHospital->id,
            'name' => 'create hospital',
            'slug' => 'app.hospitals.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHospital->id,
            'name' => 'edit hospital',
            'slug' => 'app.hospitals.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHospital->id,
            'name' => 'delete hospital',
            'slug' => 'app.hospitals.delete',
        ]);
        // Bus Route Management Module
        $moduleBusRoute = Module::updateOrCreate(['name' => 'Bus Route Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleBusRoute->id,
            'name' => 'access bus route',
            'slug' => 'app.bus_routes.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBusRoute->id,
            'name' => 'create bus route',
            'slug' => 'app.bus_routes.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBusRoute->id,
            'name' => 'edit bus route',
            'slug' => 'app.bus_routes.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBusRoute->id,
            'name' => 'delete bus route',
            'slug' => 'app.bus_routes.delete',
        ]);

        // Train Route Management Module
        $moduleTrainRoute = Module::updateOrCreate(['name' => 'Train Route Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleTrainRoute->id,
            'name' => 'access train route',
            'slug' => 'app.train_routes.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleTrainRoute->id,
            'name' => 'create train route',
            'slug' => 'app.train_routes.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleTrainRoute->id,
            'name' => 'edit train route',
            'slug' => 'app.train_routes.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleTrainRoute->id,
            'name' => 'delete train route',
            'slug' => 'app.train_routes.delete',
        ]);

        // Train Management Module
        $moduleTrain = Module::updateOrCreate(['name' => 'Train Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleTrain->id,
            'name' => 'access train',
            'slug' => 'app.trains.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleTrain->id,
            'name' => 'create train',
            'slug' => 'app.trains.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleTrain->id,
            'name' => 'edit train',
            'slug' => 'app.trains.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleTrain->id,
            'name' => 'delete train',
            'slug' => 'app.trains.delete',
        ]);

        // Launch Route Management Module
        $moduleLaunchRoute = Module::updateOrCreate(['name' => 'Launch Route Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleLaunchRoute->id,
            'name' => 'access launch route',
            'slug' => 'app.launch_routes.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleLaunchRoute->id,
            'name' => 'create launch route',
            'slug' => 'app.launch_routes.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleLaunchRoute->id,
            'name' => 'edit launch route',
            'slug' => 'app.launch_routes.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleLaunchRoute->id,
            'name' => 'delete launch route',
            'slug' => 'app.launch_routes.delete',
        ]);

        // Launch Management Module
        $moduleLaunch = Module::updateOrCreate(['name' => 'Launch Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleLaunch->id,
            'name' => 'access launch',
            'slug' => 'app.launches.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleLaunch->id,
            'name' => 'create launch',
            'slug' => 'app.launches.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleLaunch->id,
            'name' => 'edit launch',
            'slug' => 'app.launches.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleLaunch->id,
            'name' => 'delete launch',
            'slug' => 'app.launches.delete',
        ]);

        // Place Management Module
        $modulePlace = Module::updateOrCreate(['name' => 'Place Management']);
        Permission::updateOrCreate([
            'module_id' => $modulePlace->id,
            'name' => 'access place',
            'slug' => 'app.places.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $modulePlace->id,
            'name' => 'create place',
            'slug' => 'app.places.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $modulePlace->id,
            'name' => 'edit place',
            'slug' => 'app.places.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $modulePlace->id,
            'name' => 'delete place',
            'slug' => 'app.places.delete',
        ]);

        // Fire Service Management Module
        $moduleFire = Module::updateOrCreate(['name' => 'Fire Service Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleFire->id,
            'name' => 'access fire service',
            'slug' => 'app.fire_services.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleFire->id,
            'name' => 'create fire service',
            'slug' => 'app.fire_services.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleFire->id,
            'name' => 'edit fire service',
            'slug' => 'app.fire_services.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleFire->id,
            'name' => 'delete fire service',
            'slug' => 'app.fire_services.delete',
        ]);

        // House Type Management Module
        $moduleHouseType = Module::updateOrCreate(['name' => 'House Type Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleHouseType->id,
            'name' => 'access house type',
            'slug' => 'app.house_types.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHouseType->id,
            'name' => 'create house type',
            'slug' => 'app.house_types.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHouseType->id,
            'name' => 'edit house type',
            'slug' => 'app.house_types.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHouseType->id,
            'name' => 'delete house type',
            'slug' => 'app.house_types.delete',
        ]);

        // House Management Module
        $moduleHouse = Module::updateOrCreate(['name' => 'House Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleHouse->id,
            'name' => 'access house',
            'slug' => 'app.houses.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHouse->id,
            'name' => 'create house',
            'slug' => 'app.houses.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHouse->id,
            'name' => 'edit house',
            'slug' => 'app.houses.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHouse->id,
            'name' => 'delete house',
            'slug' => 'app.houses.delete',
        ]);
    }
}

