<?php


namespace Database\Seeders;

use App\Models\WorkEligibility;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

Class WorkEligibilitySeeder extends Seeder 
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        WorkEligibility::factory()->count(5)->create();
    }
}