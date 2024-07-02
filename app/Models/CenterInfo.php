<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_id', 'website', 'facebook', 'youtube', 'approval_date', 'capacity', 'description', 'register_number', 'meta_title', 'meta_description'
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

   
}
