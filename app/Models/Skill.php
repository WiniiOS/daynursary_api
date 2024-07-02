<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','skill_type_id'
    ];

    public function jobs()
    {
        return $this->belongsToMany(Job::class);
    }

    public function skillType(){
        return $this->hasOne(skillType::class);
    }
}
