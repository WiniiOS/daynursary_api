<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['title','sender_id', 'is_read',  'sender_type', 'recipient_id', 'recipient_type', 'center_id', 'application_id', 'application_type', 'type'];

    public function sender()
    {
        return $this->morphTo('sender');
    }

    public function recipient()
    {
        return $this->morphTo('recipient');
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function job()
    {
        return $this->morphTo('job');
    }

    public function application()
    {
        return $this->morphTo('application');
    }

    
    public function messages()
    {
        return $this->hasMany(ConversationMessage::class);
    }
}
