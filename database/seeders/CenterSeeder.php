<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\Service;
use App\Models\Feature;
use App\Models\CenterService;
use App\Models\CenterFeature;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\CenterInfo;
use App\Models\CenterGallery;
use App\Models\CenterSchedule;

class CenterSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Create a center with fake data
        $center = Center::create([
            'name' => $faker->name,
            'logo' => null, // If you don't want to generate images, set this to null
            'cover' => null, // Similarly, set this to null if you don't want to generate images
            'address' => $faker->address,
            'lat' => $faker->latitude(),
            'lng' => $faker->longitude(),
            'slug' => $faker->unique()->slug(),
            // 'country' => $faker->country(),
            // 'state' => $faker->state(),
            // 'city' => $faker->city(),
            'post_code' => $faker->postcode(),
    
        ]);



        // Create related CenterInfo with fake data
         CenterInfo::create([
            'center_id' => $center->id,
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,
            'website' => $faker->url,
            'facebook' => $faker->url,
            'youtube' => $faker->url,
            'approval_date' => date('Y-m-d'),
            'capacity' => $faker->numberBetween(1, 100),
            'description' => $faker->sentence,
            'register_number' => $faker->word,
            'meta_title' => $faker->word,
            'meta_description' => $faker->sentence,

            // Add other fields for CenterInfo
        ]);



        // Create related Service(s) with fake data
        // $service1 = Service::create([
        //     'name' => $faker->word,
        //     'description' => $faker->sentence,
        // ]);

        // $service2 = Service::create([
        //     'name' => $faker->word,
        //     'description' => $faker->sentence,
        // ]);

        // Attach services to the center
        $center->services()->attach([1, 2], [
            'age_group' => $faker->randomElement(['Child', 'Teen', 'Adult']),
            'price_per_day' => $faker->randomFloat(2, 10, 100),
            'status' => $faker->randomElement(['Active', 'Inactive']),
        ]);
       

        //update a center primary service  
        $randomServiceId = $center->services->random()->id;

        // Update the center's primary service ID
        $center->update(['service_id' => $randomServiceId]);

     


        // Create related CenterGallery(s) with fake data
        $gallery1 = CenterGallery::create([
            'center_id' => $center->id,
            'name' => $faker->word,
            'type' => $faker->randomElement(['Image', 'Video']),
            'link' => $faker->imageUrl(), // Assuming link is used for image URL in this case
        ]);

        $gallery2 = CenterGallery::create([
            'center_id' => $center->id,
            'name' => $faker->word,
            'type' => $faker->randomElement(['Image', 'Video']),
            'link' => $faker->imageUrl(), // Assuming link is used for image URL in this case
        ]);
        

        $gallery2 = CenterGallery::create([
            'center_id' => $center->id,
            'name' => $faker->word,
            'type' => $faker->randomElement(['Image', 'Video']),
            'link' => $faker->imageUrl(), // Assuming link is used for image URL in this case
        ]);



        $gallery2 = CenterGallery::create([
            'center_id' => $center->id,
            'name' => $faker->word,
            'type' => $faker->randomElement(['Image', 'Video']),
            'link' => $faker->imageUrl(), // Assuming link is used for image URL in this case
        ]);




        $gallery2 = CenterGallery::create([
            'center_id' => $center->id,
            'name' => $faker->word,
            'type' => $faker->randomElement(['Image', 'Video']),
            'link' => $faker->imageUrl(), // Assuming link is used for image URL in this case
        ]);



        $gallery2 = CenterGallery::create([
            'center_id' => $center->id,
            'name' => $faker->word,
            'type' => $faker->randomElement(['Image', 'Video']),
            'link' => $faker->imageUrl(), // Assuming link is used for image URL in this case
        ]);

        





        // Create related CenterSchedule(s) with fake data
        $schedule1 = CenterSchedule::create([
            'center_id' => $center->id,
            'day' => $faker->dayOfWeek,
            'morning_opening_hours' => $faker->time('H:i'),
            'morning_closing_hours' => $faker->time('H:i'),
            'afternoon_opening_hours' => $faker->time('H:i'),
            'afternoon_closing_hours' => $faker->time('H:i'),
        ]);

        $schedule2 = CenterSchedule::create([
            'center_id' => $center->id,
            'day' => $faker->dayOfWeek,
            'morning_opening_hours' => $faker->time('H:i'),
            'morning_closing_hours' => $faker->time('H:i'),
            'afternoon_opening_hours' => $faker->time('H:i'),
            'afternoon_closing_hours' => $faker->time('H:i'),
        ]);


        // Create related Feature(s) with fake data
        $feature1 = Feature::create([
            'name' => $faker->word,
            'for' => 'center',
            'image' => $faker->imageUrl(),
            'description' => $faker->sentence,
        ]);

        $feature2 = Feature::create([
            'name' => $faker->word,
            'for' => 'center',
            'image' => $faker->imageUrl(),
            'description' => $faker->sentence,
        ]);

        // Attach features to the center
        $center->features()->attach([$feature1->id, $feature2->id], [
            'image' => $faker->imageUrl(),
        ]);

        // Create related CenterService(s) with fake data
        $centerService1 = CenterService::create([
            'center_id' => $center->id,
            'service_id' => 1,
            'age_group' => $faker->randomElement(['Child', 'Teen', 'Adult']),
            'price_per_day' => $faker->randomFloat(2, 10, 100),
            'status' => $faker->randomElement(['Active', 'Inactive']),
        ]);

        $centerService2 = CenterService::create([
            'center_id' => $center->id,
            'service_id' => 2,
            'age_group' => $faker->randomElement(['Child', 'Teen', 'Adult']),
            'price_per_day' => $faker->randomFloat(2, 10, 100),
            'status' => $faker->randomElement(['Active', 'Inactive']),
        ]);

        // Create related CenterFeature(s) with fake data
        $centerFeature1 = CenterFeature::create([
            'center_id' => $center->id,
            'feature_id' => $feature1->id,
            'image' => $faker->imageUrl(),
        ]);

        $centerFeature2 = CenterFeature::create([
            'center_id' => $center->id,
            'feature_id' => $feature2->id,
            'image' => $faker->imageUrl(),
        ]);

        // Add more related data as needed

        // You can return the created center if needed
        return $center;
    }
}
