<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkExperience;
use App\Models\JobProfileImmunisation;
class JobProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'pronoun', 'dob',
        'phone_number', 'address', 'country', 'state', 'city', 'post_code',
        'work_eligibility', 'languages', 'logo', 'cover','open_to_opportunities','actively_looking',
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workExperience()
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class)->with(['role', 'jobType', 'userProfile']);
    }

    public function jobProfileImmunisation()
    {
        return $this->hasMany(JobProfileImmunisation::class);
    }

    public function jobProfileImmunisations()
    {
        return $this->hasMany(JobProfileImmunisation::class)->with('immunisation');
    }

    public function profileEducation()
    {
        return $this->hasMany(ProfileEducation::class);
    }

    public function jobProfileDocument()
    {
        return $this->hasMany(JobProfileDocument::class);
    }

    public function jobProfilePreference()
    {
        return $this->hasOne(JobProfilePreference::class);
    }

    public function jobProfileSkill()
    {
        return $this->hasMany(JobProfileSkill::class);
    }

    public function jobApplication()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function profileCertification()
    {
        return $this->hasMany(ProfileCertification::class)->with('certificate');
    }

    public function immunisations()
    {
        return $this->hasMany(JobProfileImmunisation::class);
    }
    public static function  getJobProfileRelationShips($job_profile_id){
        
        return self::with(['workExperience'])->find($job_profile_id);
    } 



    public function conversations()
    {
        return $this->morphMany(Conversation::class, 'recipient')
            ->orWhere(function ($query) {
                // Include conversations where the job profile is the sender
                $query->where('sender_type', JobProfile::class)
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
    public function JobProfileFavouriteJobs()
    {
        return $this->hasMany(JobProfileFavouriteJob::class,'jobprofile_id');
    }
    public function JobProfileViewedJobs()
    {
        return $this->hasMany(JobProfileViewedJob::class,'jobprofile_id');
    }



}
