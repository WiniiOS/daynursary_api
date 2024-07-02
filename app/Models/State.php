<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\City;

class State extends Model
{
    use HasFactory;
    //protected $primaryKey = 'name';

    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function cities(){
        return $this->hasMany(City::class);
    }
}
