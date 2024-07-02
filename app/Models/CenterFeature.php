<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_id', 'feature_id', 'image',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    // Add relationships with other tables as needed
}
