<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSkillTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data =[
            
            "Industry"=>[
               "skills"=>[
                [
                    "name"=>"Room Leader",
                    "image"=>"bi bi-people-fill"
                ],
                [
                    "name"=>"Understanding of Child Safety and Protection",
                    "image"=>"bi bi-shield-lock-fill"
                ],
                [
                    "name"=>"Understanding of National Quality Framework & Standards (NQF)",
                    "image"=>"bi bi-award-fill"

                ]
               
               ]],
               "Personal"=>[
                "skills"=>[ 
                    [
                        "name"=>"Ability to resolve conflicts with families and colleagues",
                        "image"=>"bi bi-person-hearts"
                    ],
                    [
                        "name"=>"Ability to Work as an Effective Team Member",
                        "image"=>"bi bi-person-fill-add"
                    ]
                ]
                ],
                "Technology"=>[
                    "skills"=>[

                        [
                            "name"=>"childcare Central",
                            "image"=>"bi bi-flower2"
                        ],
                        [
                            "name"=>"Childcare CRM",
                            "image"=>"bi bi-boxes"
                        ],
                    ]
                ]
                  
      ];
   
   //var_dump($data);

    foreach ($data as $skillType => $skills) {
        $skillType = SkillType::create(['name' => $skillType]);

        foreach ($skills['skills'] as $skill) {
            $state = Skill::create(['name' => $skill['name'],'image'=>$skill['image'],'skill_type_id' => $skillType->id]);
        }
    }

     
 }
    
}
