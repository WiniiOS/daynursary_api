<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProfileImmunisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_profile_id', 'immunisation_id', 'vaccination_date',
    ];

    // Relationship with job profile
    public function jobProfile()
    {
        return $this->belongsTo(JobProfile::class);
    }

    // Relationship with immunisation
    public function immunisation()
    {
        return $this->belongsTo(Immunisation::class);
    }
}
