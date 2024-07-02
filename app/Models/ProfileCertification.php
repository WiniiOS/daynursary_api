<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileCertification extends Model
{
    use HasFactory;
    protected $hidden = ['job_profile_id'];

    protected $fillable = [
        'certification_id',
        'issuing_organization',
        'job_profile_id',
        'issue_date',
        'expiration_date',
        'certificate_does_not_expire',
        'issuer_id',
        'issuer_url',
    ];

    // Relationship with certificate
    public function certificate()
    {
        return $this->belongsTo(Certification::class, 'certification_id');
    }
}
