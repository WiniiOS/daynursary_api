<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CenterService extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_id', 'service_id', 'age_group', 'price_per_day', 'status',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }


    public function applications()
    {
        return $this->belongsToMany(ChildCareApplication::class)->withTimestamps();
    }

    // Add relationships with other tables as needed
}
