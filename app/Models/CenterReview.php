<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'center_id', 'rating', 'review', 'display_name','source', 'avatar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    // Add relationships with other tables as needed
}
