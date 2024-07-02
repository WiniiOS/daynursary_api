<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            'Long day care',
            'Pre School / Kindergarten',
            'Outside School Hours Care',
            'Family day care',
            'Before school care',
            'After school care',
            'Vacation care',
        ];

        foreach ($services as $service) {
            Service::create(['name' => $service]);
        }
    }
}
