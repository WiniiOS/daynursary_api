<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $languages = [
            'English',
            'Spanish',
            'Hindi',
            'Portuguese',
            'Russian',
            'French',
            'Chinese',
            'Turkish',
            'Korean',
            'Italian',
            'German'
        ];

        foreach ($languages as $language) {
                $data = ['name' => $language];
                DB::table('languages')->insert($data);
          
        }
    }
}