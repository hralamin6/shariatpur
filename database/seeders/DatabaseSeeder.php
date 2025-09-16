<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
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
        $this->call(PageSeeder::class);

        $this->call(DivisionSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(UpazilaSeeder::class);
        $this->call(UnionSeeder::class);

        User::updateOrCreate([
            'name' => 'admin',
            'email' => 'admin@mail.com'], [
                'email_verified_at' => now(),
                'password' => bcrypt('000000'),
                'role_id' => Role::where('slug', 'admin')->first()->id,
            ]);
        User::updateOrCreate([
            'name' => 'user',
            'email' => 'user@mail.com'], [
                'email_verified_at' => now(),
                'password' => bcrypt('000000'),
                'role_id' => Role::where('slug', 'user')->first()->id,
            ]);
        $this->call(DoctorCategorySeeder::class);
        $this->call(DoctorSeeder::class);
        $this->call(HospitalSeeder::class);
        $this->call(BusRouteSeeder::class);
        $this->call(BusSeeder::class);
        $this->call(TrainRouteSeeder::class);
        $this->call(TrainSeeder::class);
        $this->call(LaunchRouteSeeder::class);
        $this->call(LaunchSeeder::class);
        $this->call(PlaceSeeder::class);
        $this->call(HotelSeeder::class);
        // Land (plots for sale)
        $this->call(LandSeeder::class);
        $this->call(RestaurantSeeder::class);
        $this->call(BeautyParlorSeeder::class);
        $this->call(EntrepreneurSeeder::class);
        $this->call(WorkSeeder::class);
        $this->call(FireServiceSeeder::class);
        $this->call(ElectricityOfficeSeeder::class);
        $this->call(HouseTypeSeeder::class);
        $this->call(HouseSeeder::class);
        $this->call(ServicemanTypeSeeder::class);
        $this->call(ServicemanSeeder::class);
        $this->call(CarTypeSeeder::class);
        $this->call(CarSeeder::class);
        $this->call(SellCategorySeeder::class);
        $this->call(SellSeeder::class);
        $this->call(CourierServiceSeeder::class);
        $this->call(DiagnosticCenterSeeder::class);
        $this->call(PoliceSeeder::class);
        $this->call(LawyerSeeder::class);
        $this->call(LostFoundSeeder::class);
        $this->call(InstitutionTypeSeeder::class);
        $this->call(InstitutionSeeder::class);
        $this->call(NoticeSeeder::class);
        $this->call(BloodDonorSeeder::class);
        $this->call(HotlineSeeder::class);
        $this->call(TutorSeeder::class);

        $this->call(BlogCategorySeeder::class);
        $this->call(BlogCategorySeeder::class);
        $this->call(NewsCategorySeeder::class);
        $this->call(NewsSeeder::class);

        // Create 5 parent categories
        //        $parentCategories = Category::factory()->count(5)->create();

        // Create 3 child categories for each parent category
        //        $parentCategories->each(function ($parentCategory) {
        //            Category::factory()->count(3)->childCategory($parentCategory->id)->create();
        //        });

        // Create 10 users
        //        $users = User::factory()->count(10)->create();

        // For each user, create 10 posts with random child categories
        //        $users->each(function ($user) {
        //            Post::factory()->count(10)->create([
        //                'user_id' => $user->id,
        //                'category_id' => Category::whereNotNull('parent_id')->inRandomOrder()->first()->id, // Random child category
        //            ]);
        //        });
    }
}
