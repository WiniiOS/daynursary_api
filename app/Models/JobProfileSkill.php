<?php

namespace App\Models;

use App\Http\Resources\V1\Skill_Resource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Skill;

class JobProfileSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_profile_id', 'skill_id', 'skill_level'
    ];

     // Relationship with skill
     public function user()
     {
         return $this->belongsTo(Skill::class);
     }

     public function skill()
     {
         return new Skill_Resource($this->belongsTo(Skill::class));
     }
}
