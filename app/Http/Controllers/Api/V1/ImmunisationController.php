<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\ImmunisationRequest;
use App\Http\Resources\V1\ImmunisationResource;
use App\Models\Immunisation;
use App\Services\ImmunisationService;
use Exception;


class ImmunisationController extends Controller
{
    protected $immunisationService;
    public function __construct(ImmunisationService $immunisationService)
    {
        $this->immunisationService = $immunisationService;
    }

    public function add_immu (ImmunisationRequest $request){
        $data = $request->validated();

        $response = $this->immunisationService->createImmunisation($data);
        return $response;
    }

    public function update_immu (ImmunisationRequest $request,$id){
        $immu  = $request->validated();
        $response = $this->immunisationService->edit_immunisation($immu, $id );
        return $response;

    }

    public function delete_immu($id){
        $response = $this->immunisationService->delete_immunisation($id);
        return $response;

    }
    public function immu_list(){
        try{
        $immu = Immunisation::all();
        return ImmunisationResource::collection($immu);

        }catch(\Exception $exception){
            info($exception);
            return apiResponse([$exception],'Network Error',500);
        }
    }
}
