<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserStatusSeeder;

use Database\Seeders\RolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserStatusSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(WorkEligibilitySeeder::class);
        $this->call(CenterSeeder::class);
        $this->call(JobSeeder::class);
        $this->call(JobRoleSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(JobTypeSeeder::class);
        $this->call(JobProfileSeeder::class);
        $this->call(WorkExperienceSeeder::class);
        $this->call(ProfileEducationSeeder::class);
        $this->call(JobProfileImmunisationSeeder::class);
        $this->call(LanguageSeeder::class);

        
    }
}
