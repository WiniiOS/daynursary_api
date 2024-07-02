<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProfileViewedJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'jobprofile_id', 'job_id'
    ];
    
    public function JobProfile(){
        return $this->belongsTo(JobProfile::class);
     }
 
     public function Job(){
        return  $this->belongsTo(Job::class);
     }
     // Relationship with skill
    
}
