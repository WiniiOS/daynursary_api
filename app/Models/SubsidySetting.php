<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsidySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'annual_income', 'parent_activity_level',
    ];

    public function parent()
    {
        return $this->belongsTo(Parent::class);
    }

    // Add relationships with other tables as needed
}
