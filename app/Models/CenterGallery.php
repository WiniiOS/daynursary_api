<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_id', 'name', 'type', 'link',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    // Add relationships with other tables as needed
}
