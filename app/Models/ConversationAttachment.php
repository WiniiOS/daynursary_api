<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ConversationAttachment extends Model
{
    use HasFactory; 
       protected $fillable = ['conversation_message_id','file_name', 'file_type', 'file_path'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }



    // public function storeFile($file)
    // {
    //     // Store the file on S3 and get the file path
    //     $filePath = Storage::disk('s3')->put('conversation_attachments', $file);

    //     // Update the file_path attribute in the model
    //     $this->file_path = $filePath;

    //     return $this;
    // }


    function storeFile($file)
    {  
        
        $filePath = 'daynursary/conversations'.'/' . str_replace(' ', '-', $file->getClientOriginalName());
      
        Storage::disk('s3')->put($filePath, file_get_contents($file)); 
        $this->file_path = $filePath;  
          return  $this;  
    }


}
