<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GHLAccessToken extends Model
{
    use HasFactory;
    
    protected $table = 'ghl_access_tokens';
    protected $fillable = [
        'access_token',
        'token_type',
        'expires_in',
        'refresh_token',
        'scope',
        'userType',
        'companyId',
        'userId',
    ];

  
   
}
