<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobsFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          
        $featureGroups = [
            // Financial Benefits
            'Financial Benefits' => [
                'Above Award Rates',
                'Educator Discount',
                'Employee Referral Program',
                'Paid Parental Leave',
                'Retail Partnerships & Discounts',
                'Staff Discount',
            ],

            // Transfer and Relocation Opportunities
            'Transfer and Relocation Opportunities' => [
                'Health and Wellbeing',
                'Employee Assistance Program',
                'Health and Wellness Programs',
                'Health Insurance Benefits',
                'Wellbeing Days',
            ],

            // Workplace Benefits
            'Workplace Benefits' => [
                'Dedicated Support Office',
                'Increased Programming Time for Educators',
                'Reward and Recognition Program',
            ],

            // Work Life Balance
            'Work Life Balance' => [
                'Close to Public Transport',
                'No weekend work',
            ],

            // Career Development and Support
            'Career Development and Support' => [
                'Career Development Support',
                'Scholarships',
                'Study Support',
                'Traineeships or Apprenticeships Available',
            ],
        ];


        // foreach ($featureGroups as $groupName => $features) {
        //     $parentFeature = ['name' => $groupName, 'slug' => Str::slug($groupName), 'type' => 'parent', 'image' => '', 'parent_feature_id' => '', 'for' => '', 'description' => ''];
        //     $parentFeatureId = DB::table('features')->insertGetId($parentFeature);

        //     foreach ($features as $featureName) {
        //         $feature = ['name' => $featureName, 'slug' => Str::slug($featureName), 'type' => '', 'image' => '', 'parent_feature_id' => $parentFeatureId, 'for' => 'childcare', 'description' => ''];
        //         DB::table('features')->insert($feature);
        //     }
        // }     



        foreach ($featureGroups as $groupName => $features) {
            $parentFeature = ['name' => $groupName, 'slug' => Str::slug($groupName), 'for' => 'job', 'type' => 'parent', 'image' => '', 'parent_feature_slug' => '',  'description' => ''];
            $parentFeatureId = DB::table('features')->insertGetId($parentFeature);

            foreach ($features as $featureName) {
                $feature = ['name' => $featureName, 'slug' => Str::slug($featureName),'for' => 'job', 'type' => 'child', 'image' => '', 'parent_feature_slug' => Str::slug($groupName),  'description' => ''];
                DB::table('features')->insert($feature);
            }
        }


    }
}
