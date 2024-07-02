<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use App\Models\Feature;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $featureGroups = [
        //     // Parent Services
        //     'Parent Services' => [
        //         'Approved for CCS',
        //         'Car Parking',
        //         'Credit Card Payments Accepted',
        //         'Daily Activity Updates',
        //         'Direct Debit Facility',
        //         'Parent App',
        //         'Storypark Families App',
        //         'Waitlist',
        //     ],

        //     // Fees & Discounts
        //     'Fees & Discounts' => [
        //         'Additional Sibling Discount',
        //         'Educator Staff Discounts',
        //         'Employee Staff Discounts',
        //         'Flexible Sessions',
        //         'Multi-Day Discounts',
        //         'No Fees on Public Holidays',
        //     ],

        //     // Additional Services
        //     'Additional Services' => [
        //         'Can Accommodate Special Needs',
        //     ],

        //     // Educators & Staff
        //     'Educators & Staff' => [
        //         'Tertiary Qualified Staff',
        //     ],

        //     // Centre Facilities & Services
        //     'Centre Facilities & Services' => [
        //         'Allergy Aware',
        //         'Environmentally Friendly',
        //         'Nappies Provided',
        //         'SunSafe aware',
        //     ],

        //     // Activities & Equipment
        //     'Activities & Equipment' => [
        //         'Indoor Play Area',
        //         'Outdoor Play Area',
        //         'Shaded Outdoor Area',
        //     ],

        //     // Food & Nutrition
        //     'Food & Nutrition' => [
        //         'All Meals Provided',
        //     ],

        //     // Learning Activities
        //     'Learning Activities' => [
        //         'Approved Preschool Program',
        //         'Education & Development Programs',
        //     ],
        // ];

        // foreach ($featureGroups as $groupName => $features) {
        //     $parentFeature = ['name' => $groupName, 'slug' => Str::slug($groupName), 'for' => 'childcare', 'type' => 'parent', 'image' => '', 'parent_feature_slug' => '',  'description' => ''];
        //     $parentFeatureId = DB::table('features')->insertGetId($parentFeature);

        //     foreach ($features as $featureName) {
        //         $feature = ['name' => $featureName, 'slug' => Str::slug($featureName),'for' => 'childcare', 'type' => 'child', 'image' => '', 'parent_feature_slug' => Str::slug($groupName),  'description' => ''];
        //         DB::table('features')->insert($feature);
        //     }
        // }


        $jobFeatureGroups = [
            // Financial Benefits
            'Financial Benefits' => [
                'Above Award Rates',
                'Complimentary uniforms',
                'Educator Discount',
                'Employee Referral Program',
                'Negotiable Rates',
                'Retail Partnerships & Discounts',
                'Staff Discount',
                'Transfer and Relocation Opportunities'
            ],
            'Health and Wellbeing'=>[
                'Employee Assistance Program',
                'Health and Wellness Programs'
            ],
            'Workplace Benefits'=>[
                'Dedicated Support Office',
                'Reward and Recognition Program'
            ],
            'Work Life Balance'=>[
                'Breakfast Provided',
                'Flexible Hours/Days',
                'Close to Public Transport',
                'No weekend work'
            ],
            'Career Development and Support'=>[
                'Career Development Support',
                'Traineeships or Apprenticeships Available',
                'Training and Development'
            ]


        ];

        foreach ($jobFeatureGroups as $groupName => $features) {
            $parentFeature = ['name' => $groupName, 'slug' => Str::slug($groupName), 'for' => 'job', 'type' => 'parent', 'image' => '', 'parent_feature_slug' => '',  'description' => ''];
            $parentFeatureId = DB::table('features')->insertGetId($parentFeature);

            foreach ($features as $featureName) {
                $feature = ['name' => $featureName, 'slug' => Str::slug($featureName),'for' => 'job', 'type' => 'child', 'image' => '', 'parent_feature_slug' => Str::slug($groupName),  'description' => ''];
                DB::table('features')->insert($feature);
            }
        }
    }
}

