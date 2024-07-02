<?php 
namespace App\Services;

use App\Models\State;
use App\Models\ParentProfile;
use App\Models\ParentChild;
use App\Models\Role;
use App\Models\ProfileEducation;
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

class EducationService
{  

    public function addEducation($data) {
        try {   
         
            $existing_education = ProfileEducation::where(
                'qualification', $data['qualification'])
            ->where('start_date', $data['start_date'])
            ->where('job_profile_id', $data['job_profile_id'])
            ->where('school', $data['school'])->first();

            if (!empty($existing_education)) {

                return apiResponse([], 'this education and qualification already exists for this job profile', 409); 
            }

            // Store the profile education data
           if ($education_qualification = ProfileEducation::create($data)) {

                return apiResponse($education_qualification, 'success', 201); 
            }

        }catch (\Exception $exception) {
           
            info($exception);
        }

    }

    public function updateProfileEducation($data) {
        try {   
            // update the existing Profile education data
            $profileEducation = ProfileEducation::find($data['id']);

            $existing_education = ProfileEducation::where(
                'qualification', $data['qualification'])
            ->where('start_date', $data['start_date'])
            ->where('end_date', $data['end_date'])
            ->where('description', $data['description'])
            ->where('currently_studying', $data['currently_studying'])
            ->where('job_profile_id', $data['job_profile_id'])
            ->where('field_of_study', $data['field_of_study'])
            ->where('school', $data['school'])
            ->where('id', '<>', $data['id'])->first();

            if (!empty($existing_education)) {
                return apiResponse([], 'this profile education already exists for this job profile', 409); 
            }
            if ($work_experience = $profileEducation->update($data)) {

                return apiResponse($profileEducation, 'success', 201); 
            }

        }catch (\Exception $exception) {
           
            info($exception);
        }

    }
}