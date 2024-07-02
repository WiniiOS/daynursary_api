<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileEducation extends Model
{
    use HasFactory;

    protected $table = 'profile_educations';

    protected $fillable = [
        'job_profile_id', 'qualification', 'field_of_study', 'school',
        'start_date', 'end_date', 'currently_studying', 'description',
    ];

    // Relationship with job profile
    public function jobProfile()
    {
        return $this->belongsTo(JobProfile::class);
    }
}
