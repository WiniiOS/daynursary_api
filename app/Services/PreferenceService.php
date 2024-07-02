<?php 
namespace App\Services;

use App\Models\JobProfilePreference;
use App\Models\JobProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash; 
use App\Http\Resources\V1\ProfileResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;

class PreferenceService
{  

    public function addProfilePreference($data) {
        try {   
         
            $existing_preference = JobProfilePreference::where(
                'jobs_interested', $data['jobs_interested'])
            ->where('companies_selection', $data['companies_selection'])
            ->where('salary', $data['salary'])
            ->where('distance_covered', $data['distance_covered'])
            ->where('start_type', $data['start_type'])
            ->where('start_type', $data['start_type'])
            ->where('days', $data['days'])
            ->where('jobs', $data['jobs'])
            ->where('job_profile_id', $data['job_profile_id'])->first();

            if (!empty($existing_preference)) {

                return apiResponse([], 'this profile preference already exists for this job profile', 409); 
            }

            // Store the profile preference data
           if ($preference = JobProfilePreference::create($data)) {

                return apiResponse($preference, 'success', 201); 
            }

        }catch (\Exception $exception) {
           
            info($exception);
        }

    }

    public function updateProfilePreference($data) {
        try {   
            // update the existing Profile preference data
            $jobProfilePreference = JobProfilePreference::find($data['id']);

            $existing_preference = JobProfilePreference::where(

                'jobs_interested', $data['jobs_interested'])
            ->where('companies_selection', $data['companies_selection'])
            ->where('salary', $data['salary'])
            ->where('distance_covered', $data['distance_covered'])
            ->where('start_type', $data['start_type'])
            ->where('start_type', $data['start_type'])
            ->where('days', $data['days'])
            ->where('jobs', $data['jobs'])
            ->where('job_profile_id', $data['job_profile_id'])
            ->where('id', '<>', $data['id'])->first();

            if (!empty($existing_preference)) {
                return apiResponse([], 'this profile preference already exists for this job profile', 409); 
            }
            if ($jobProfilePreference->update($data)) {

                return apiResponse($jobProfilePreference, 'success', 201); 
            }

        }catch (\Exception $exception) {
           
            info($exception);
        }

    }
}