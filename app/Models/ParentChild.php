<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentChild extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_profile_id', 'first_name', 'last_name', 'image', 'dob', 'gender', 'child_allergies', 'special_needs',
    ];

    public function parentProfile()
    {
        return $this->belongsTo(ParentProfile::class);
    }

    // Add relationships with other tables as needed
}
