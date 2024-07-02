<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProfileDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_profile_id', 'type', 'name', 'link','kind'
    ];

    // Relationship with job profile
    public function jobProfile()
    {
        return $this->belongsTo(JobProfile::class);
    }
}
