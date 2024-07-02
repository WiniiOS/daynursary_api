<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'message', 'status', 'conversation_id', 'user_type', ];




    public function attachments()
    {
        return $this->hasMany(ConversationAttachment::class);
    }



    public function user()
    {
        return $this->morphTo('user');
    } 


}
