<?php 
namespace App\Services;

use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\ParentProfile;
use App\Models\ParentChild;
use App\Models\Role;
use App\Models\WorkExperience;
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
use App\Http\Resources\V1\JobProfileResource;

class JobProfileService
{  

    public function myWorkExpirience($data) {
        try {   
         
            $existing_experience = WorkExperience::where(
                'company_name', $data['company_name'])
            ->where('start_date', $data['start_date'])
            ->where('end_date', $data['end_date'])
            ->where('currently_working', $data['currently_working'])
            ->where('description', $data['description'])
            ->where('job_profile_id', $data['job_profile_id'])
            ->where('job_type_id', $data['job_type_id'])
            ->where('role_id', $data['role_id'])->first();

            if (!empty($existing_experience)) {
                return response()->json([
                    'message' => 'false',
                    'error' => 'this work experience already exists for this job profile '
                ], 409); 
            }

            // Store the experience data
           if ($work_experience = WorkExperience::create($data)) {

                return response()->json([
                    'message' => 'success',
                    'WorkExperience' => $work_experience
                ], 201);
            }

        }catch (\Exception $exception) {
           
            info($exception);
        }

    }

    public function updateMyExpirience($data) {
        try {   
            // update the existing experience data
            $experience = WorkExperience::find($data['id']);

            $existing_experience = WorkExperience::where(
                'company_name', $data['company_name'])
            ->where('start_date', $data['start_date'])
            ->where('end_date', $data['end_date'])
            ->where('currently_working', $data['currently_working'])
            ->where('description', $data['description'])
            ->where('job_profile_id', $data['job_profile_id'])
            ->where('id', '<>', $data['id'])
            ->where('job_type_id', $data['job_type_id'])
            ->where('role_id', $data['role_id'])->first();

            if (!empty($existing_experience)) {
                return response()->json([
                    'message' => 'false',
                    'error' => 'this work experience already exists for this job profile '
                ], 409); 
            }
            if ($work_experience = $experience->update($data)) {

                return response()->json([
                    'message' => 'success',
                    'WorkExperience' => $experience
                ], 201); 
            }

        }catch (\Exception $exception) {
           
            info($exception);
        }

    }

    public function getJobProfile($id){

        try{
             
            $jobProfile =  JobProfile::getJobProfileRelationShips($id);

            return response()->json(['jobProfile' => new JobProfileResource($jobProfile)], 200);

            
        }catch(ModelNotFoundException $exception){ 

          
            return response([ 'errors' => 'profile not found', 'message' => 'profile not found'], 400);

        }catch (\Exception $exception) {

            return response()->json(['errors' => 'An error occurred ' . $exception], 500);

        }
    }


}