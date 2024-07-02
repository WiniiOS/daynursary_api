<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

    class CenterAdmin extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'username',
        'center_id',
        'ghl_user_id',
        'location_id',
        'email',
        'password',
    ];    

    
    protected $hidden = [
        'password',
        'auth_token',
    ];


    
    public function centers()
    {
        return $this->hasMany(Center::class, 'center_admin_id');
    }
    
  


    public function conversations()
    {  

        $columns = [ 'conversations.sender_type', 'conversations.recipient_id', 'conversations.recipient_type', 'conversations.application_id', 'conversations.application_type', 'conversations.title', 'conversations.type', 'conversations.created_at', 'conversations.updated_at'];


            $centerConversations = Conversation::whereHasMorph('recipient', [Center::class], function ($query) {
            
                $query->where('center_admin_id', $this->id);
              
            });   

        $jobConversations = Conversation::whereHasMorph('recipient', [Job::class], function ($query) {
            $query->whereHas('center', function ($subquery) {
                $subquery->where('center_admin_id', $this->id);
            });
        });

       
        $unionedConversations = $centerConversations->union($jobConversations);

        return $unionedConversations;
    } 


    // public function conversations()
    // {
    //     $centerConversations = $this->hasManyThrough(Conversation::class, Center::class, 'center_admin_id', 'recipient_id')
    //         ->select('conversations.*');

    //     $jobConversations = Conversation::whereHasMorph('recipient', [Job::class], function ($query) {
    //         $query->whereHas('center', function ($subquery) {
    //             $subquery->where('center_admin_id', $this->id);
    //         });
    //     })->select('conversations.*');

    //   return $jobConversations ;
    // }



    
   
   
    
}

