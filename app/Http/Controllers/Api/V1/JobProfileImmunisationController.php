<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParentProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\JobProfile;
use App\Models\Immunisation;
use App\Models\JobProfileImmunisation;
use Illuminate\Support\Facades\Auth;
use App\Services\JobImmunisationService;
use Illuminate\Validation\Rule;

class JobProfileImmunisationController extends Controller
{
    protected $jobImmunisationService;

    public function __construct(JobImmunisationService $jobImmunisationService)
    {
        $this->jobImmunisationService = $jobImmunisationService;
    }

    // add a new profile immunisation 
    public function createProfileImmunisation(Request $request) {
        $data = $request->all();

        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([], 'User  not found or user not logged', 404); 
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return apiResponse([], 'Job profile  not found', 404);
        }

        JobProfileImmunisation::where('job_profile_id', $job_profile->id)->delete();
        foreach ($data as $item) {
            $data_object = [
                'job_profile_id'=>$job_profile->id,
                'immunisation_id'=>$item['id'],
                'vaccination_date'=>$item['date']
            ];

            $validated = Validator::make($data_object, [
                'vaccination_date' => 'date_format:Y-m-d|nullable',
                'immunisation_id' => 'required|numeric|distinct|exists:App\Models\Immunisation,id'
            ]);
    
            if($validated->fails()){
    
                return error_processor($validated);
     
            }
            $data = array_merge($data_object);
        
            $response = $this->jobImmunisationService->addJobImmunisation($data);
        }
        return $response;
    }

    // update existing job profile Immunisation
    public function updateProfileImmunisation(Request $request, $id) {
        $data = $request->only(
            'vaccination_date', 
            'immunisation_id'
        );

        $jobProfileImmunisation = JobProfileImmunisation::find($id);
        if(empty($jobProfileImmunisation)) {
            return apiResponse([],'profile Immunisation not found', 404); 
        }

        $validated = Validator::make($data, [
            'vaccination_date' => 'date_format:Y-m-d',
            'immunisation_id' => 'numeric|distinct|exists:App\Models\Immunisation,id'
        ]);

        if($validated->fails()){

            return error_processor($validated);
 
        }

        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([], 'User  not found or user not logged', 404); 
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return apiResponse([], 'Job profile  not found', 404);
        }
        $data = array_merge($data, ['id' => $id, 'job_profile_id' => $job_profile->id]);


        $data['immunisation_id'] = isset($data['immunisation_id'])?$data['immunisation_id']:$jobProfileImmunisation->immunisation_id;
        $data['vaccination_date'] = isset($data['vaccination_date'])?$data['vaccination_date']:$jobProfileImmunisation->vaccination_date;

        $response = $this->jobImmunisationService->updateJobImmunisation($data); 
        return $response;
    }

    //get all  profile_immunisations and get one by id
    public function profile_immunisation(Request $request, $id=null) {

        if (isset($id)) {
            $profile_immunisation = JobProfileImmunisation::find($id);
            if ($profile_immunisation) {

                return apiResponse($profile_immunisation,  'success', 201);

            } else {
                return apiResponse([], 'Profile immunisation not found', 404); 
            }  
        }
        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([], 'User  not found or user not logged', 404); 
        }
        $job_profile = $user->jobProfile;
        if ($job_profile) {
            $profile_immunisations = JobProfileImmunisation::where('job_profile_id', $job_profile->id)->with('immunisation')->get();

            return apiResponse($profile_immunisations,  'success', 201);
            
        } else {
            return apiResponse([], 'Job profile not deleted', 404); 
        }
    }

    //delete an existing profile_immunisation 
    public function delete_immunisation(Request $request, $id) {

        $immunisation = JobProfileImmunisation::find($id);

        if ($immunisation->delete()) {

            return apiResponse([], 'success', 201);
        } else {
            return apiResponse([], 'Profile immunisation not deleted', 404); 
        }
    }

}