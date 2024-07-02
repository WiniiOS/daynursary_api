<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_id', 'day', 'morning_opening_hours', 'morning_closing_hours', 'afternoon_opening_hours', 'afternoon_closing_hours'
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    // Add relationships with other tables as needed
}
