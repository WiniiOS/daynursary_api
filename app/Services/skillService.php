<?php
namespace App\Services;

use App\Http\Resources\V1\Skill_Resource;
use App\Models\Skill;
use App\Models\SkillType;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class SkillService{



    public function createSkill($data){
    // Your logic to create user profile
        try{ 

           $skill = Skill::create($data);
           
            return response()->json([
                'status'=>'success',
                'message' => 'skill successfully created',
                'profile' => $skill
            ], 201); 


        }catch (\Exception $exception) {
            return response()->json(['errors' => 'An error occurred ' . $exception], 500);
        }
}

        public function getSkills()
    {
        try{
             
            
            $skill = Skill::all();

            return response()->json(['skills' => Skill_Resource::collection($skill)], 200);

    

        }catch (\Exception $exception) {

            return response()->json(['errors' => 'An error occurred ' . $exception], 500);

        }
        
    } 

    public function getSkill($id){
        try{
             
            
            $skill = Skill::findOrFail($id);

            return response()->json(['skill' =>new Skill_Resource($skill)], 200);

    

        }catch (\Exception $exception) {

            return response()->json(['errors' => 'An error occurred ' . $exception], 500);

        }

    }

    public function updateSkill($data, $id)
    {
        // Your logic to update user profile data  

        try{ 

            $skill = Skill::findOrFail($id);
            $datas = array_filter($data);
            $skill->update($datas);
 
            return response(['data' => ['skill' => $skill],'status'=>'success', 'message' => 'skill updated successfully'], 200);
            
        }catch(ModelNotFoundException $exception){
           
            return response(['error' => 'skill not found', 'message' => 'profile not found'], 404);
        }catch (\Exception $exception) { 
            return response(['error' => 'An error occurred '.$exception, 'message' => 'An error occurred'], 500);
           
        }

    } 


    public function deleteSkill($id){

        try{
            $skill = Skill::findOrFail($id);
        
            $skill->delete();
            return response([ 'message' => 'skill deleted'], 200);
           
            
        }catch(ModelNotFoundException $exception){
           
            return response([ 'errors' => 'skill not found', 'message' => 'skill not found'], 400);
            
        }catch (\Exception $exception) {
           
            return response([ 'errors' => 'An error occurred', 'message' => 'An error occurred'], 500); 
        }
    } 
    

    //skill type

    public function createSkillType($data){
        try{ 

            $SkillType = SkillType::create($data);
            
             return response()->json([
                 'status'=>'success',
                 'message' => 'skill type successfully created',
                 'skillType' => $SkillType
             ], 201); 
 
 
         }catch (\Exception $exception) {
             return response()->json(['errors' => 'An error occurred ' . $exception], 500);
         }
    }
    public function getSkillTypes(){
        try{
             
            
            $skillTypes = SkillType::all();

            return response()->json(['skillTypes' => Skill_Resource::collection($skillTypes)], 200);

    

        }catch (\Exception $exception) {

            return response()->json(['errors' => 'An error occurred ' . $exception], 500);

        }
    }
    public function getSkillType($id){
        try{
             
            
            $SkillType = SkillType::findOrFail($id);

            return response()->json(['skillType' =>new Skill_Resource($SkillType)], 200);

        }catch(ModelNotFoundException $exception){
           
            return response([ 'errors' => 'skill type not found', 'message' => 'skill type not found'], 404);
            
        }
        catch (\Exception $exception) {

            return response()->json(['errors' => 'An error occurred ' . $exception], 500);

        }
    }

    public function updateSkillType($data,$id){
        try{ 

            $SkillType = SkillType::findOrFail($id);
            $datas = array_filter($data);
            $SkillType->update($datas);
 
            return response(['data' => ['skillType' => $SkillType],'status'=>'success', 'message' => 'skill type updated successfully'], 200);
            
        }catch(ModelNotFoundException $exception){
           
            return response(['error' => 'skill type not found', 'message' => 'skill type not found'], 404);
        }catch (\Exception $exception) { 
            return response(['error' => 'An error occurred '.$exception, 'message' => 'An error occurred'], 500);
           
        }
    }
    public function deleteSkillType($id){
        try{
            $SkillType = SkillType::findOrFail($id);
        
            $SkillType->delete();
            return response([ 'message' => 'skill type deleted'], 200);
           
            
        }catch(ModelNotFoundException $exception){
           
            return response([ 'errors' => 'skill type not found', 'message' => 'skill type not found'], 404);
            
        }catch (\Exception $exception) {
           
            return response([ 'errors' => 'An error occurred', 'message' => 'An error occurred'], 500); 
        }
    }
}