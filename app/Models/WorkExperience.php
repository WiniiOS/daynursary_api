<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_profile_id', 'role_id', 'company_name', 'job_type_id',
        'start_date', 'end_date', 'currently_working', 'description',
    ];

    // Relationship with user profile
    public function userProfile()
    {
        return $this->belongsTo(ParentProfile::class);   
    }

    // Relationship with job role
    public function role()
    {
        return $this->belongsTo(JobRole::class);
    }

    // Relationship with job type
    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }
}
