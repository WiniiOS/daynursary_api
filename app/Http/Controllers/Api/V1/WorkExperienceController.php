<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SkillResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParentProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\JobProfile;
use App\Models\JobType;
use App\Models\JobRole;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\Auth;
use App\Services\JobProfileService;
use Illuminate\Validation\Rule;

class WorkExperienceController extends Controller
{
    protected $jobProfileService;

    public function __construct(JobProfileService $jobProfileService)
    {
        $this->jobProfileService = $jobProfileService;
    }

    // add a new work experience
    public function createWorkEperience(Request $request)
    {
        $data = $request->only(
            'company_name',
            'start_date',
            'end_date',
            'currently_working',
            'description',
            'job_type_id',
            'role_id'
        );

        $validated = Validator::make($data, [
            'company_name' => 'required|string|min:3',
            'start_date' => 'date_format:Y-m-d',
            'end_date' => 'date_format:Y-m-d|nullable',
            'currently_working' => 'boolean',
            'description' => 'nullable|string|min:10',
            'job_type_id' => 'nullable|numeric|distinct|exists:App\Models\JobType,id',
            'role_id' => 'required|numeric|distinct|exists:App\Models\JobRole,id',
        ]);

        if ($validated->fails()) {
            return apiResponse( error_processor($validated),$validated->errors()->first(),200);

            // return response()->json(['errors' => error_processor($validated)], 422);

        }

        $user = Auth::user();
        if (empty($user)) {
            return response()->json(['errors' => 'User  not found or user not logged'], 404);
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return response()->json(['errors' => 'Job profile  not found'], 404);
        }

        $data = array_merge($data, ['job_profile_id' => $job_profile->id]);

        $data['start_date'] = isset($data['start_date']) ? $data['start_date'] : null;
        $data['currently_working'] = isset($data['currently_working']) ? $data['currently_working'] : null;
        $data['description'] = isset($data['description']) ? $data['description'] : null;

        $response = $this->jobProfileService->myWorkExpirience($data);
        return $response;
    }

    // update existing work experience
    public function updateWorkEperience(Request $request, $id)
    {
        $data = $request->only(
            'company_name',
            'start_date',
            'end_date',
            'currently_working',
            'description',
            'job_type_id',
            'role_id'
        );

        $workexperience = WorkExperience::find($id);
        if (empty($workexperience)) {
            return response()->json(['errors' => 'Work experience not found'], 404);
        }

        $validated = Validator::make($data, [
            'company_name' => 'string|min:3',
            'start_date' => 'date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'currently_working' => 'boolean',
            'description' => 'nullable|string|min:10',
            'job_type_id' => 'nullable|numeric|distinct|exists:App\Models\JobType,id',
            'role_id' => 'numeric|distinct|exists:App\Models\JobRole,id',
        ]);

        if ($validated->fails()) {

            return apiResponse( error_processor($validated),$validated->errors()->first(),200);

        }

        $user = Auth::user();
        if (empty($user)) {
            return response()->json(['errors' => 'User  not found or user not logged'], 404);
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return response()->json(['errors' => 'Job profile  not found'], 404);
        }
        $data = array_merge($data, ['id' => $id, 'job_profile_id' => $job_profile->id]);

        $data['company_name'] = isset($data['company_name']) ? $data['company_name'] : $workexperience->company_name;
        $data['start_date'] = isset($data['start_date']) ? $data['start_date'] : $workexperience->start_date;
        $data['end_date'] = isset($data['end_date']) ? $data['end_date'] : $workexperience->end_date;
        $data['end_date'] = isset($data['end_date']) ? $data['end_date'] : $workexperience->end_date;
        $data['currently_working'] = isset($data['currently_working']) ? $data['currently_working'] : $workexperience->currently_working;
        $data['description'] = isset($data['description']) ? $data['description'] : $workexperience->description;
        $data['job_type_id'] = isset($data['job_type_id']) ? $data['job_type_id'] : $workexperience->job_type_id;
        $data['role_id'] = isset($data['role_id']) ? $data['role_id'] : $workexperience->role_id;

        $response = $this->jobProfileService->updateMyExpirience($data);
        return $response;
    }

    //get all  job types and get one by id
    public function job_types(Request $request, $id = null)
    {

        if (isset($id)) {
            $type = JobType::find($id);
            if ($type) {
                return response()->json([
                    'message' => 'success',
                    'job_types' => $type
                ], 201);
            } else {
                return response()->json(['errors' => 'Job type not found'], 404);
            }
        }
        $types = JobType::all();
        return response()->json([
            'message' => 'success',
            'job_types' => $types,
            'Total' => $types->count()
        ], 201);
    }

    //get all job roles and get one by id
    public function job_roles(Request $request, $id = null)
    {

        if (isset($id)) {
            $role = JobRole::find($id);
            if ($role) {
                return response()->json([
                    'message' => 'success',
                    'job_types' => $role
                ], 201);
            } else {
                return response()->json(['errors' => 'Job role not found'], 404);
            }
        }
        $roles = JobRole::all();
        return response()->json([
            'message' => 'success',
            'job_types' => $roles,
            'Total' => $roles->count()
        ], 201);
    }

    //get all  work experiences related to a user and get one by id
    public function work_experience(Request $request, $id = null)
    {

        if (isset($id)) {
            $experience = WorkExperience::find($id);
            if ($experience) {
                return response()->json([
                    'message' => 'success',
                    'job_types' => $experience
                ], 201);
            } else {
                return response()->json(['errors' => 'Work experience not found'], 404);
            }
        }

        $user = Auth::user();
        if (empty($user)) {
            return response()->json(['errors' => 'User  not found or user not logged'], 404);
        }
        $job_profile = $user->jobProfile;

        if ($job_profile) {
            $experiences = WorkExperience::where('job_profile_id', $job_profile->id)->with('role', 'jobType')->get();

            return apiResponse([$experiences], 'success', 201);

        } else {
            return response()->json(['errors' => 'Job profile not deleted'], 404);
        }
    }

    //delete an existing work experience 
    public function delete_work_experience(Request $request, $id)
    {

        $experience = WorkExperience::find($id);

        if ($experience->delete()) {

            return response()->json(['success' => true, 'message' => 'work_experience deleted successful'], 201);
        } else {
            return response()->json(['errors' => 'Work experience not not deleted'], 404);
        }
    }

    //delete an existing work experience 
    public function delete_job_role(Request $request, $id)
    {

        $jobRole = JobRole::find($id);

        if ($jobRole->delete()) {

            return response()->json(['success' => true, 'message' => 'jobRole deleted successful'], 201);
        } else {
            return response()->json(['errors' => 'Job role not deleted'], 404);
        }
    }

    //delete an existing work experience 
    public function delete_job_type(Request $request, $id)
    {

        $job_type = JobType::find($id);

        if ($job_type->delete()) {

            return response()->json(['success' => true, 'message' => 'job_type deleted successful'], 201);
        } else {
            return response()->json(['errors' => ' Job type not deleted'], 404);
        }
    }

    //get all data related to job profile
    public function job_profile_data(Request $request)
    {

        $user = Auth::user();
        if (empty($user)) {
            return response()->json(['errors' => 'User  not found or user not logged'], 404);
        }
        $job_profile = JobProfile::where('user_id', $user->id)->with(['workExperiences', 'jobProfileImmunisations', 'profileEducation',
            'JobProfileDocument', 'jobProfilePreference', 'profileCertification'])->first();

        $job_profile->jobProfileSkills= SkillResource::collection($job_profile->jobProfileSkill);

        return apiResponse(['jobProfile' => $job_profile], 'success', 201);

    }

}
