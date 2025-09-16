<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
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

        // Sponsor Management Module
        $moduleSponsor = Module::updateOrCreate(['name' => 'Sponsor Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleSponsor->id,
            'name' => 'access sponsor',
            'slug' => 'app.sponsors.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleSponsor->id,
            'name' => 'create sponsor',
            'slug' => 'app.sponsors.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleSponsor->id,
            'name' => 'edit sponsor',
            'slug' => 'app.sponsors.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleSponsor->id,
            'name' => 'delete sponsor',
            'slug' => 'app.sponsors.delete',
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

        // Notice Management Module
        $moduleNotice = Module::updateOrCreate(['name' => 'Notice Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleNotice->id,
            'name' => 'access notice',
            'slug' => 'app.notices.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNotice->id,
            'name' => 'create notice',
            'slug' => 'app.notices.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNotice->id,
            'name' => 'edit notice',
            'slug' => 'app.notices.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNotice->id,
            'name' => 'delete notice',
            'slug' => 'app.notices.delete',
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

        // Diagnostic Center Management Module
        $moduleDiagnostic = Module::updateOrCreate(['name' => 'Diagnostic Center Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleDiagnostic->id,
            'name' => 'access diagnostic center',
            'slug' => 'app.diagnostic_centers.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleDiagnostic->id,
            'name' => 'create diagnostic center',
            'slug' => 'app.diagnostic_centers.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleDiagnostic->id,
            'name' => 'edit diagnostic center',
            'slug' => 'app.diagnostic_centers.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleDiagnostic->id,
            'name' => 'delete diagnostic center',
            'slug' => 'app.diagnostic_centers.delete',
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

        // Hotel Management Module
        $moduleHotel = Module::updateOrCreate(['name' => 'Hotel Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleHotel->id,
            'name' => 'access hotel',
            'slug' => 'app.hotels.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHotel->id,
            'name' => 'create hotel',
            'slug' => 'app.hotels.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHotel->id,
            'name' => 'edit hotel',
            'slug' => 'app.hotels.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHotel->id,
            'name' => 'delete hotel',
            'slug' => 'app.hotels.delete',
        ]);

        // Restaurant Management Module
        $moduleRestaurant = Module::updateOrCreate(['name' => 'Restaurant Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleRestaurant->id,
            'name' => 'access restaurant',
            'slug' => 'app.restaurants.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleRestaurant->id,
            'name' => 'create restaurant',
            'slug' => 'app.restaurants.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleRestaurant->id,
            'name' => 'edit restaurant',
            'slug' => 'app.restaurants.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleRestaurant->id,
            'name' => 'delete restaurant',
            'slug' => 'app.restaurants.delete',
        ]);

        // Hotline Management Module
        $moduleHotline = Module::updateOrCreate(['name' => 'Hotline Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleHotline->id,
            'name' => 'access hotline',
            'slug' => 'app.hotlines.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHotline->id,
            'name' => 'create hotline',
            'slug' => 'app.hotlines.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHotline->id,
            'name' => 'edit hotline',
            'slug' => 'app.hotlines.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleHotline->id,
            'name' => 'delete hotline',
            'slug' => 'app.hotlines.delete',
        ]);

        // Entrepreneur Management Module
        $moduleEntrepreneur = Module::updateOrCreate(['name' => 'Entrepreneur Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleEntrepreneur->id,
            'name' => 'access entrepreneur',
            'slug' => 'app.entrepreneurs.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleEntrepreneur->id,
            'name' => 'create entrepreneur',
            'slug' => 'app.entrepreneurs.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleEntrepreneur->id,
            'name' => 'edit entrepreneur',
            'slug' => 'app.entrepreneurs.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleEntrepreneur->id,
            'name' => 'delete entrepreneur',
            'slug' => 'app.entrepreneurs.delete',
        ]);

        // Serviceman Type Management Module
        $moduleServicemanType = Module::updateOrCreate(['name' => 'Serviceman Type Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleServicemanType->id,
            'name' => 'access serviceman types',
            'slug' => 'app.serviceman_types.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleServicemanType->id,
            'name' => 'create serviceman type',
            'slug' => 'app.serviceman_types.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleServicemanType->id,
            'name' => 'edit serviceman type',
            'slug' => 'app.serviceman_types.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleServicemanType->id,
            'name' => 'delete serviceman type',
            'slug' => 'app.serviceman_types.delete',
        ]);

        // Serviceman Management Module
        $moduleServiceman = Module::updateOrCreate(['name' => 'Serviceman Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleServiceman->id,
            'name' => 'access serviceman',
            'slug' => 'app.servicemen.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleServiceman->id,
            'name' => 'create serviceman',
            'slug' => 'app.servicemen.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleServiceman->id,
            'name' => 'edit serviceman',
            'slug' => 'app.servicemen.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleServiceman->id,
            'name' => 'delete serviceman',
            'slug' => 'app.servicemen.delete',
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

        // Electricity Office Management Module
        $moduleElectricity = Module::updateOrCreate(['name' => 'Electricity Office Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleElectricity->id,
            'name' => 'access electricity office',
            'slug' => 'app.electricity_offices.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleElectricity->id,
            'name' => 'create electricity office',
            'slug' => 'app.electricity_offices.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleElectricity->id,
            'name' => 'edit electricity office',
            'slug' => 'app.electricity_offices.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleElectricity->id,
            'name' => 'delete electricity office',
            'slug' => 'app.electricity_offices.delete',
        ]);

        // Courier Service Management Module
        $moduleCourier = Module::updateOrCreate(['name' => 'Courier Service Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleCourier->id,
            'name' => 'access courier service',
            'slug' => 'app.courier_services.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleCourier->id,
            'name' => 'create courier service',
            'slug' => 'app.courier_services.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleCourier->id,
            'name' => 'edit courier service',
            'slug' => 'app.courier_services.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleCourier->id,
            'name' => 'delete courier service',
            'slug' => 'app.courier_services.delete',
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

        // Car Type Management Module
        $moduleCarType = Module::updateOrCreate(['name' => 'Car Type Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleCarType->id,
            'name' => 'access car type',
            'slug' => 'app.car_types.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleCarType->id,
            'name' => 'create car type',
            'slug' => 'app.car_types.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleCarType->id,
            'name' => 'edit car type',
            'slug' => 'app.car_types.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleCarType->id,
            'name' => 'delete car type',
            'slug' => 'app.car_types.delete',
        ]);

        // Car Management Module
        $moduleCar = Module::updateOrCreate(['name' => 'Car Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleCar->id,
            'name' => 'access car',
            'slug' => 'app.cars.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleCar->id,
            'name' => 'create car',
            'slug' => 'app.cars.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleCar->id,
            'name' => 'edit car',
            'slug' => 'app.cars.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleCar->id,
            'name' => 'delete car',
            'slug' => 'app.cars.delete',
        ]);

        // Sell Category Management Module
        $moduleSellCategory = Module::updateOrCreate(['name' => 'Sell Category Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleSellCategory->id,
            'name' => 'access sell category',
            'slug' => 'app.sell_categories.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleSellCategory->id,
            'name' => 'create sell category',
            'slug' => 'app.sell_categories.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleSellCategory->id,
            'name' => 'edit sell category',
            'slug' => 'app.sell_categories.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleSellCategory->id,
            'name' => 'delete sell category',
            'slug' => 'app.sell_categories.delete',
        ]);

        // Sell Management Module
        $moduleSell = Module::updateOrCreate(['name' => 'Sell Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleSell->id,
            'name' => 'access sell',
            'slug' => 'app.sells.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleSell->id,
            'name' => 'create sell',
            'slug' => 'app.sells.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleSell->id,
            'name' => 'edit sell',
            'slug' => 'app.sells.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleSell->id,
            'name' => 'delete sell',
            'slug' => 'app.sells.delete',
        ]);

        // Blog Category Management Module
        $moduleBlogCategory = Module::updateOrCreate(['name' => 'Blog Category Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleBlogCategory->id,
            'name' => 'access blog category',
            'slug' => 'app.blog_categories.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBlogCategory->id,
            'name' => 'create blog category',
            'slug' => 'app.blog_categories.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBlogCategory->id,
            'name' => 'edit blog category',
            'slug' => 'app.blog_categories.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBlogCategory->id,
            'name' => 'delete blog category',
            'slug' => 'app.blog_categories.delete',
        ]);

        // Blog Management Module
        $moduleBlog = Module::updateOrCreate(['name' => 'Blog Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleBlog->id,
            'name' => 'access blog',
            'slug' => 'app.blogs.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBlog->id,
            'name' => 'create blog',
            'slug' => 'app.blogs.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBlog->id,
            'name' => 'edit blog',
            'slug' => 'app.blogs.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBlog->id,
            'name' => 'delete blog',
            'slug' => 'app.blogs.delete',
        ]);

        // News Category Management Module
        $moduleNewsCategory = Module::updateOrCreate(['name' => 'News Category Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleNewsCategory->id,
            'name' => 'access news category',
            'slug' => 'app.news_categories.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNewsCategory->id,
            'name' => 'create news category',
            'slug' => 'app.news_categories.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNewsCategory->id,
            'name' => 'edit news category',
            'slug' => 'app.news_categories.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNewsCategory->id,
            'name' => 'delete news category',
            'slug' => 'app.news_categories.delete',
        ]);

        // News Management Module
        $moduleNews = Module::updateOrCreate(['name' => 'News Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleNews->id,
            'name' => 'access news',
            'slug' => 'app.news.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNews->id,
            'name' => 'create news',
            'slug' => 'app.news.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNews->id,
            'name' => 'edit news',
            'slug' => 'app.news.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleNews->id,
            'name' => 'delete news',
            'slug' => 'app.news.delete',
        ]);

        // Blood Donor Management Module
        $moduleBloodDonor = Module::updateOrCreate(['name' => 'Blood Donor Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleBloodDonor->id,
            'name' => 'access blood donors',
            'slug' => 'app.blood_donors.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBloodDonor->id,
            'name' => 'create blood donor',
            'slug' => 'app.blood_donors.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBloodDonor->id,
            'name' => 'edit blood donor',
            'slug' => 'app.blood_donors.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleBloodDonor->id,
            'name' => 'delete blood donor',
            'slug' => 'app.blood_donors.delete',
        ]);

        // Police Management Module
        $modulePolice = Module::updateOrCreate(['name' => 'Police Management']);
        Permission::updateOrCreate([
            'module_id' => $modulePolice->id,
            'name' => 'access police',
            'slug' => 'app.police.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $modulePolice->id,
            'name' => 'create police',
            'slug' => 'app.police.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $modulePolice->id,
            'name' => 'edit police',
            'slug' => 'app.police.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $modulePolice->id,
            'name' => 'delete police',
            'slug' => 'app.police.delete',
        ]);
        // Institution Type Management Module
        $moduleInstitutionType = Module::updateOrCreate(['name' => 'Institution Type Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleInstitutionType->id,
            'name' => 'access institution types',
            'slug' => 'app.institution_types.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleInstitutionType->id,
            'name' => 'create institution type',
            'slug' => 'app.institution_types.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleInstitutionType->id,
            'name' => 'edit institution type',
            'slug' => 'app.institution_types.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleInstitutionType->id,
            'name' => 'delete institution type',
            'slug' => 'app.institution_types.delete',
        ]);

        // Institution Management Module
        $moduleInstitution = Module::updateOrCreate(['name' => 'Institution Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleInstitution->id,
            'name' => 'access institutions',
            'slug' => 'app.institutions.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleInstitution->id,
            'name' => 'create institution',
            'slug' => 'app.institutions.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleInstitution->id,
            'name' => 'edit institution',
            'slug' => 'app.institutions.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleInstitution->id,
            'name' => 'delete institution',
            'slug' => 'app.institutions.delete',
        ]);

        // Work Management Module
        $moduleWork = Module::updateOrCreate(['name' => 'Work Management']);
        Permission::updateOrCreate([
            'module_id' => $moduleWork->id,
            'name' => 'access work',
            'slug' => 'app.works.index',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleWork->id,
            'name' => 'create work',
            'slug' => 'app.works.create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleWork->id,
            'name' => 'edit work',
            'slug' => 'app.works.edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $moduleWork->id,
            'name' => 'delete work',
            'slug' => 'app.works.delete',
        ]);
    }
}
