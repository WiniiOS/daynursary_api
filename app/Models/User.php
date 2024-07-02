<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'google_id',
        'facebook_id',
        'email',
        'password',
        'api_token',
        'uuid',
        'last_login_at',
        'created_by',
        'status_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(ParentProfile::class);
    }

    public function jobProfile()
    {
        return $this->hasOne(JobProfile::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id');
    }

    public function FavouriteCenters()
    {
        return $this->hasMany(UserFavoriteCenter::class, 'user_id');
    }
}
