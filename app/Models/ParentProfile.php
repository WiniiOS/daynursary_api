<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentProfile extends Model
{
    use HasFactory;

    //protected $primaryKey = 'parent_profile_id';

    protected $fillable = [
        'first_name',
        'image',
        'last_name',
        'email',
        'phone',
        'dob',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'centrelink',
        'parent_profile_id',
        'user_id',
        // 'postcode'

    ];




    public function parent()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_profile_id');
    } 




    public function subProfiles()
    {
        return $this->hasMany(ParentProfile::class, 'parent_profile_id');
    }




    // public function children() {
    //     return $this->hasMany(ParentChild::class);  
    // }

    public function children()
{
    return $this->hasMany(ParentChild::class, 'parent_profile_id');
}
     


     /**
     * Eager load relationships when fetching a parent profile.
     */
    public static function getProfileWithRelationships($profile_id) 
    {
        return self::with(['parent',  'subProfiles', 'children'])->find($profile_id);
    }




    // public function conversations()
    // {
    //     return $this->morphMany(Conversation::class, 'recipient');
    // }
    


    public function conversations()
    {
        return $this->morphMany(Conversation::class, 'recipient')
            ->orWhere(function ($query) {
                // Include conversations where the parent profile is the sender
                $query->where('sender_type', ParentProfile::class)
                      ->where('sender_id', $this->id);
            })
            ->with([
                'messages' => function ($query) {
                    // Include the last message sent in each conversation
                    $query->latest()->first();
                },
                'sender', // Include details about the sender
                'recipient', // Include details about the recipient
                'application' => function ($query) {
                    // Conditionally include application details if the type is 'application'
                    $query->when(
                        $this->type === 'application',
                        function ($q) {
                            $q->with('applicationDetails'); // Replace with the actual relationship name
                        }
                    );
                },
            ]);
    }



}
