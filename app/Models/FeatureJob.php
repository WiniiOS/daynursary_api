<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_id', 'job_id',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

}
