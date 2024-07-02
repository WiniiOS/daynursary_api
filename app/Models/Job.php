<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',  'cover', 'job_type', 'job_info', 'service_to_render', 'start_date','slug',
        'min_pay','center_id', 'max_pay', 'pay_type', 'work_eligibility','due_date','benefits'
    ];

    protected $casts = [
        'work_eligibility'=>'array'
        ,'benefits'=>'array'
    ];

    // Add additional methods or relationships as needed

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
    


    public function certifications()
    {
        return $this->belongsToMany(Certification::class,'job_certification');
    }
    


    public function features()
    {
        return $this->belongsToMany(Feature::class,'job_feature');
    }
    

 
    public function conversations()
    {
        return $this->morphMany(Conversation::class, 'job');
    } 

    public function center()
    {
        return $this->belongsTo(Center::class, 'center_id');
    } 

     //languages
     public function languages(){
        return $this->belongsToMany(Language::class,'job_language');
    }

    public function immunisations(){
        return $this->belongsToMany(Immunisation::class,'job_immunisation');
    }



public static function jobWithRelationships($id) 
{
    return self::with(['center',  'certifications', 'features','skills','immunisations','languages'])->find($id);
}
    

}
