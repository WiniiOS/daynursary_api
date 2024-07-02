<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentersClaim extends Model
{
    use HasFactory;

    protected $table = 'centers_claims';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'verified',
        'verification_code',
        'center_id',
    ];



}
