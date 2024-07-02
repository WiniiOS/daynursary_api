<?php 
namespace App\Services;
use App\Models\Immunisation;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ImmunisationService{
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

    public function edit_immunisation($data,$id){
        try{ 

            $immu = Immunisation::findOrFail($id);
            $immu->update($data);
            

            return apiResponse($immu, 'success', 200);
            
        }catch(ModelNotFoundException $exception){

            return apiResponse([], 'immunisation not found', 404);
        
        }catch (\Exception $exception) { 
            return apiResponse([], 'An error occurred', 500);
           
        }
    }
    public function delete_immunisation($id){
        try{ 

            $immu = Immunisation::findOrFail($id);
            $immu->delete();
            

            return apiResponse($immu, 'success', 200);
            
        }catch(ModelNotFoundException $exception){

            return apiResponse([], 'immunisation not found', 404);
        
        }catch (\Exception $exception) { 
            info($exception);
            return apiResponse([], 'An error occurred', 500);
           
        }

    }
}