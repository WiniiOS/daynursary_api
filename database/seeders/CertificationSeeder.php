<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Certification;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Certification::count() === 0) {
        Certification::create([
            'name' => 'Anaphylaxis Certificate',
        ]);

        Certification::create([
            'name' => 'Asthma Certificate',
        ]);
        Certification::create([
            'name' => 'Child Protection Certificate',
        ]);
        Certification::create([
            'name' => 'Child Safety and Protection',
        ]);
        Certification::create([
            'name' => 'Conflict Resolution Training',
        ]);
        Certification::create([
            'name' => 'CPR Certificate',
        ]);
        Certification::create([
            'name' => 'Criminal Check',
        ]);
        Certification::create([
            'name' => "Driver's License",
        ]);
        Certification::create([
            'name' => 'First Aid Certificate',
        ]);
        Certification::create([
            'name' => 'Food Safety Certificate',
        ]);
        Certification::create([
            'name' => 'Food Safety Supervisor Certificate',
        ]);
        Certification::create([
            'name' => 'Overseas Teachning Qualification Assessment',
        ]);
        Certification::create([
            'name' => 'Teachers Registration',
        ]);
        Certification::create([
            'name' => 'Working With Children Check',
        ]);
        Certification::create([
            'name' => 'Other',
        ]);
    }
}
}
