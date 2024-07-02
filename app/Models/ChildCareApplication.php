<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCareApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_id', 'parent_id', 'additional_parent_id', 'child_id', 'days', 'date', 'status',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentProfile::class);
    }

    public function additionalParent()
    {
        return $this->belongsTo(ParentProfile::class, 'additional_parent_id');
    }

    public function child()
    {
        return $this->belongsTo(ParentChild::class);
    }
    


    public function centerServices()
    {
        return $this->belongsToMany(CenterService::class, 'application_center_service')->withTimestamps();
        
    }    


   
    

    

    // Add relationships with other tables as needed
}
