<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProfilePreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_profile_id', 'jobs_interested', 'companies_selection',
        'salary', 'distance_covered', 'start_type', 'start_date',
        'days', 'jobs',
    ];
    //declaring arrays
    protected $casts = [
        'jobs_interested' => 'array',
        'companies_selection' => 'array',
        'salary' => 'array',
        'days' => 'array',
        'jobs' => 'array'
    ];

    // Relationship with job profile
    public function jobProfile()
    {
        return $this->belongsTo(JobProfile::class);
    }
}
