<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookATour extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','first_name', 'last_name', 'telephone', 'address','email','totalVisitors','child_first_name','child_last_name','childs','message','choosed_time','choosed_date','done'
    ]; 

    // public function children(){
    //     return $this->hasMany(Childs::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}