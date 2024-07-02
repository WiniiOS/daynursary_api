<?php

namespace App\Http\Controllers\Api\V1\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\storeSkillRequest;
use App\Services\ProfileSkillService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as Status;
use App\Http\Requests\V1\StoreSkillTypeRequest;
use App\Models\JobProfileSkill;
use  App\Services\SkillService;
use App\Models\JobProfile;
use Illuminate\Support\Facades\Response;


class ProfileSkillController extends Controller
{
    protected $jobProfileSkillService;
    protected $skillService;

    public function __construct(ProfileSkillService $jobProfileSkillService,SkillService $skillService)
    {
        $this->jobProfileSkillService = $jobProfileSkillService;
        $this->skillService = $skillService;
    }

  
    //save skills
    public function saveSkills(Request $request){
        $selectedSkills = $request->input('skills');
        info($selectedSkills);
        try{

        
            //get user is
            $user = Auth::user();
            if(empty($user)){
                return apiResponse([],'user not logged in', 409);
            }
            $job_profile = $user->jobProfile;
            if (empty($job_profile)) {
                return apiResponse([],'Job profile not found', 404);
            }
            

            //remove any existing field not present in the selected

            $existingSkills = $job_profile->jobProfileSkill->pluck('skill_id')->toArray();
            $skillsToDelete = array_diff($existingSkills, array_column($selectedSkills, 'id'));
            info($skillsToDelete);
            $job_profile->jobProfileSkill()->whereIn('skill_id', $skillsToDelete)->delete();

            //update the fields

            foreach ($selectedSkills as $selectedSkill) {
                $skillId = $selectedSkill['id'];
                $skillLevel = $selectedSkill['level'];
            
                $jobProfileSkill = $job_profile->jobProfileSkill()->where('skill_id', $skillId)->first();
            
                if ($jobProfileSkill) {
                    $jobProfileSkill->update(['skill_level' => $skillLevel]);
                } else {
                    $job_profile->jobProfileSkill()->create([
                        'skill_id' => $skillId,
                        'skill_level' => $skillLevel,
                    ]);
                }
            }

            return apiResponse([],'success',200);
        }catch(\Exception $exception){
            info($exception);
            return apiResponse([],'An unexpected error occured',500);

        }
        
    }

    public function getSkills(): JsonResponse
    {
        $skills = $this->jobProfileSkillService->getAllSkills();
        return response()->json($skills);
    }


    public function createSkill(Request $request)
    {
        $data = $request->only(
            'skill_id',
            'skill_level',
        );

        $validated = Validator::make($data, [
            'skill_id' => 'required|numeric|distinct|exists:App\Models\Skill,id',
            'skill_level' => 'string',
        ]);

        if ($validated->fails()) {

            return response()->json(['errors' => error_processor($validated)], 422);

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

        $data['skill_id'] = isset($data['skill_id']) ? $data['skill_id'] : null;
        $data['skill_level'] = isset($data['skill_level']) ? $data['skill_level'] : null;

        $response = $this->jobProfileSkillService->createSkill($data);
        return $response;
    }

    public function updateSkill(Request $request, $id)
    {
        $data = $request->only(
            'skill_id',
            'skill_level',
        );

        $JobProfileSkill = JobProfileSkill::find($id);
        if (empty($JobProfileSkill)) {
            return response()->json(['errors' => 'Work experience not found'], 404);
        }

        $validated = Validator::make($data, [
            'skill_id' => 'required|numeric|distinct|exists:App\Models\Skill,id',
            'skill_level' => 'string|min:8',
        ]);

        if ($validated->fails()) {

            return response()->json(['errors' => error_processor($validated)], 422);

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

        $data['skill_id'] = isset($data['skill_id']) ? $data['skill_id'] : $JobProfileSkill->skill_id;
        $data['skill_level'] = isset($data['skill_level']) ? $data['skill_level'] : $JobProfileSkill->skill_level;

        $response = $this->jobProfileSkillService->updateSkill($id, $data);
        return $response;
    }

    public function deleteSkill($id): JsonResponse
    {
        $deleted = $this->jobProfileSkillService->deleteSkill($id);

        if ($deleted) {
            return apiResponse([],'success',200);
        } else {
            return   apiResponse([],'job profile skill not found',404);
        }
    }
    //skill api
    public function create_skill (storeSkillRequest $request){
        $data = $request->validated();

        $response = $this->skillService->createSkill($data);
        return $response;

    }

    public function get_skills (){
         
        $response = $this->skillService->getSkills();
        return $response; 

    }
    public function update_skill(storeSkillRequest $request,$id){
        $data = $request->validated();
        $response = $this->skillService->updateSkill($data,$id);
        return $response;
         
    }

    public function delete_skill ($id){
        $response = $this->skillService->deleteSkill ($id);
        return $response;
    }
    //skill type

    public function create_skill_type(StoreSkillTypeRequest $request){
        $data = $request->validated();

        $response = $this->skillService->createSkillType($data);
        return $response;
    }

    public function get_skill_type($id){
        $response = $this->skillService->getSkillType($id);
        return $response;

    }

    public function get_skill_types(){
        $response = $this->skillService->getSkillTypes();
        return $response;
    }

    public function update_skill_type(StoreSkillTypeRequest $request,$id){
        $data = $request->validated();
        $response = $this->skillService->updateSkillType($data,$id);
        return $response;

    }

    public function delete_skill_type($id){
        $response = $this->skillService->deleteSkillType ($id);
        return $response;

    }

    //update job status
    public function update_job_status(Request $request){
        $open_to_opportunities = $request->input('open_to_opportunities');
        $actively_looking= $request->input('actively_looking');
        // $data = [
        //     'open_to_opportunities'=>$open_to_opportunities,
        //     'actively_looking'=>$actively_looking
        // ];
        $data['open_to_opportunities'] =$open_to_opportunities;
        $data['actively_looking'] = $actively_looking;
        info($data);

        $selectedSkills = $request->input('skills');
        info($selectedSkills);
        try{

        
            //get user is
            $user = Auth::user();
            if(empty($user)){
                return apiResponse([],'user not logged in', 409);
            }
            $job_profile = $user->jobProfile;
            if (empty($job_profile)) {
                return apiResponse([],'Job profile not found', 404);
            }
             $job_profile->update($data);
            

            return apiResponse([],'success',200);
        }catch(\Exception $exception){
            info($exception);
            return apiResponse([],'An unexpected error occured',500);

        }
        
    }
}
