<?php 
namespace App\Services;

use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\JobProfile;
use App\Models\Immunisation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash; 
use App\Http\Resources\V1\ProfileResource;
use App\Models\JobProfileImmunisation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\V1\JobProfileResource;

class jobImmunisationService
{  

    public function addJobImmunisation($data) {
        try {   
         
            $jobProfileImmunisation = JobProfileImmunisation::where(
                'immunisation_id', $data['immunisation_id'])
                ->where('vaccination_date', $data['vaccination_date'])->first();

            if (!empty($jobProfileImmunisation)) {
                return apiResponse([], 'this profile Immunication already exists for this job profile ', 409); 
            }

            // Store the job profile immunisation data
           if ($jobProfileImmunisation = JobProfileImmunisation::create($data)) {

            return apiResponse($jobProfileImmunisation, 'success',  201);
            }

        }catch (\Exception $exception) {
           
            info($exception);
        }

    }

    public function updateJobImmunisation($data) {
        try {   
            // update the existing job Profile Immunisation data
            $jobProfile_immunisation = JobProfileImmunisation::find($data['id']);

            $jobProfileImmunisation = JobProfileImmunisation::where(
                'immunisation_id', $data['immunisation_id'])
                ->where('id', '<>', $data['id'])
            ->where('vaccination_date', $data['vaccination_date'])->first();

            if (!empty($jobProfileImmunisation)) {
                return apiResponse([], 'this work job Profile Immunisation already exists for this job profile ', 409); 
            }
            if ($immunisation = $jobProfile_immunisation->update($data)) {

                return apiResponse($jobProfile_immunisation, 'success', 201); 
            }

        }catch (\Exception $exception) {
           
            info($exception);
        }

    }

    //create an immunisation
    public function createImmunisation($data){
        try{
            $immu=Immunisation::create([
                'name'=>$data['name'],
                'description'=>$data['description']
            ]);
           
            return apiResponse($immu, 'success', 200);

        }catch(\Exception $exception){
            info($exception);
            return apiResponse([], 'an unexpected error occured!', 500);
        }

    }

    public function edit_immunisation($data, $id){
        
    }

    

}