<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_profile_id', 'job_id', 'status',
    ];

    // Relationship with job profile
    public function jobProfile()
    {
        return $this->belongsTo(JobProfile::class);
    }

    // Relationship with user
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    
}
