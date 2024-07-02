<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'for',  'image', 'description', 'slug', 'parent_feature_id',
    ];

    // Relationship with parent feature

    public function jobs()
    {
        return $this->belongsToMany(Job::class);
    }


    public function center()
    {
        return $this->belongsToMany(Center::class);
    }



    
    public function parentFeature()
    {
        return $this->belongsTo(Feature::class, 'parent_feature_id');
    }

    // Relationship with child features
    public function childFeatures()
    {
        return $this->hasMany(Feature::class, 'parent_feature_id');
    }
}
