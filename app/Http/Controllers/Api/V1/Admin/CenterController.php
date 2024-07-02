<?php    
 
 namespace App\Http\Controllers\Api\V1\Admin; 

 use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CenterFavouriteResource;
use App\Http\Resources\V1\AdminCenterResource;
use App\Models\Center;
use App\Models\CenterService as ServiceCenter;
use App\Models\UserFavoriteCenter;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Validation\Rule;
 use App\Services\CenterService;
 use Illuminate\Support\Facades\Auth;
 use App\Models\CenterFeature;
 use App\Models\Feature;
 use App\Models\CenterSchedule;
 use Carbon\Carbon;
 
 


 class CenterController extends Controller
 {
    
    protected $centerService;

    public function __construct(CenterService $centerService)
    {
        $this->centerService = $centerService;
    }


 

    public function getAllCenters(){
     
           // $user = request()->vendor;

            $centers = Center::all();
            $data = AdminCenterResource::collection($centers);
            return apiResponse($centers,'success',200);
      
       
    } 


    public function getCenterDetails($slug){

         $center = Center::where('slug',$slug)->first();
         $data = new AdminCenterResource($center);

         return apiResponse($data,'success',200);
   
    
    }



    public function add_serviceCenter(Request $request)
    {  
        $data = $request->all();
        $validate = Validator::make($data, [
            'age_group' => 'required|string',
            'price_per_day' => 'required|numeric|min:0', 
            'status' => 'in:active,inactive',
            "center_id"  => 'required|numeric|distinct|exists:App\Models\Center,id',
            "service_id"  => 'required|numeric|distinct|exists:App\Models\Service,id'
        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        } 

        $existingservice = ServiceCenter::where('center_id', $data['center_id'])
            ->where('service_id', $data['service_id'])->first();
            if (!empty($existingservice)) {

                return apiResponse([], 'This Center service already exists',409);

            }
        
        if ($service = ServiceCenter::create($data)) {

            return apiResponse($data,'success',200);
        }
        
        return apiResponse([],'Bad request',400);
    }

    public function update_serviceCenter(Request $request, $id)
    {  
        $data = $request->all();
        $validate = Validator::make($data, [
            'age_group' => 'string',
            'price_per_day' => 'numeric|min:0', 
            'status' => 'in:active,inactive',
            "center_id"  => 'numeric|distinct|exists:App\Models\Center,id',
            "service_id"  => 'numeric|distinct|exists:App\Models\Service,id'
        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        }

        $center_service = ServiceCenter::find($id);
        if(empty($center_service)) {

            return apiResponse([], 'not found',404);
        }

            $existingservice = ServiceCenter::where('center_id', $data['center_id'])
                ->where('service_id', $data['service_id']) 
                ->where('id', '<>', $id)->first();
            if (!empty($existingservice)) {

                return apiResponse([], 'This service already exists',409);

            }
        
        if ($center_service->update($data)) {

            return apiResponse(ServiceCenter::find($id),'success',200);
        }

        return apiResponse([],'Bad request',400);
    }

    public function get_serviceCenter(Request $request, $id=null) {
        
        if(isset($id)) {
            $center_service = ServiceCenter::find($id);
            if(empty($center_service)) {

                return apiResponse([], 'not found',404);
            }

            return apiResponse($center_service,'success',200);
        }

        $center_service = ServiceCenter::all();

        return apiResponse($center_service,'success',200);

    }

    public function delete_serviceCenter(Request $request, $id) {

        $center_service = ServiceCenter::find($id);
        if(empty($center_service)) {

            return apiResponse([], 'not found',404);
        }

        if ($center_service->delete()) {

            return apiResponse([], 'success', 201);

        } else {
            return apiResponse([], 'Center Service detail not deleted', 400); 
        }
    }

    public function add_centerFeature(Request $request){

        $data = $request->all();
        $validate = Validator::make($data, [
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|file',
            'center_id'  => 'required|numeric|distinct|exists:App\Models\Center,id',
            'feature_id'  => 'required|numeric|distinct|exists:App\Models\Feature,id',

        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        }


        $existing_center_feature = CenterFeature::where('feature_id', $data['feature_id'])
            ->where('center_id', $data['center_id'])->first();

        if (!(strtolower($data['status']) === "active")) {

            if ($existing_center_feature->delete()) {

                return apiResponse([], 'Feature successful delete', 201);

            } 
            
            return apiResponse([], 'Feature  not deleted', 400); 
        }

        $file = $request->file('image');

        if(isset($fille)) {
            $file_path = storeFile($file);
            $link = env("AWS_URL").'/'.$file_path;

            $data = array_merge($data, ['image' => $link]);
        }


        if (!empty($existing_center_feature)) {

            return apiResponse([], 'This center feature already exists',409);
        }
        
        if ($centerFeature = CenterFeature::create($data)) {

            return apiResponse($centerFeature,'Center feature successfull save',200);
        }
        
        return apiResponse([],'Bad request',400);

    }

    public function add_centerSchedule(Request $request){

        $data = $request->all();
        $validate = Validator::make($data, [
            'day' => 'required|string',
            'morning_opening_hours' => 'required|date_format:H:i',
            'morning_closing_hours' => 'required|date_format:H:i',
            'afternoon_opening_hours' => 'required|date_format:H:i',
            'afternoon_closing_hours' => 'required|date_format:H:i',
            'center_id'  => 'required|numeric|distinct|exists:App\Models\Center,id',

        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        }

        $data['morning_opening_hours'] = Carbon::createFromFormat('H:i', $data['morning_opening_hours'])->format('H:i');
        $data['morning_closing_hours'] = Carbon::createFromFormat('H:i', $data['morning_closing_hours'])->format('H:i');
        $data['afternoon_opening_hours'] = Carbon::createFromFormat('H:i', $data['afternoon_opening_hours'])->format('H:i');
        $data['afternoon_closing_hours'] = Carbon::createFromFormat('H:i', $data['afternoon_closing_hours'])->format('H:i');

        $existing_centerSchedule = CenterSchedule::where('day', $data['day'])
            ->where('morning_opening_hours', $data['morning_opening_hours'])
            ->where('morning_closing_hours', $data['morning_closing_hours'])
            ->where('afternoon_opening_hours', $data['afternoon_opening_hours'])
            ->where('afternoon_closing_hours', $data['afternoon_closing_hours'])
            ->where('center_id', $data['center_id'])->first();


        if (!empty($existing_centerSchedule)) {

            return apiResponse([], 'This center schedule already exists',409);
        }

        if ($centerSchedule = CenterSchedule::create($data)) {

            return apiResponse($centerSchedule,'Center schedule successfull save',200);
        }
        
        return apiResponse([],'Bad request',400);

    }


    public function update_centerSchedule(Request $request, $id){

        $data = $request->all();
        $validate = Validator::make($data, [
            'day' => 'string',
            'morning_opening_hours' => 'date_format:H:i',
            'morning_closing_hours' => 'date_format:H:i',
            'afternoon_opening_hours' => 'date_format:H:i',
            'afternoon_closing_hours' => 'date_format:H:i',
            'center_id'  => 'required|numeric|distinct|exists:App\Models\Center,id',

        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        }

        $centerSchedule = CenterSchedule::find($id);
        if(empty($centerSchedule)) {

            return apiResponse([], 'not found',404);
        }

        if (isset($data['morning_opening_hours'])){
            $data['morning_opening_hours'] = Carbon::createFromFormat('H:i', $data['morning_opening_hours'])->format('H:i');
        }
        if (isset($data['morning_closing_hours'])){
            $data['morning_closing_hours'] = Carbon::createFromFormat('H:i', $data['morning_closing_hours'])->format('H:i');
        }
        if (isset($data['afternoon_opening_hours'])){
            $data['afternoon_opening_hours'] = Carbon::createFromFormat('H:i', $data['afternoon_opening_hours'])->format('H:i');
        }
        if (isset($data['afternoon_closing_hours'])){
            $data['afternoon_closing_hours'] = Carbon::createFromFormat('H:i', $data['afternoon_closing_hours'])->format('H:i');
        }

        $existing_centerSchedule = CenterSchedule::where('day', $data['day'])
            ->where('morning_opening_hours', $data['morning_opening_hours'])
            ->where('morning_closing_hours', $data['morning_closing_hours'])
            ->where('afternoon_opening_hours', $data['afternoon_opening_hours'])
            ->where('afternoon_closing_hours', $data['afternoon_closing_hours'])
            ->where('center_id', $data['center_id'])
            ->where('id', '<>', $id)->first();


        if (!empty($existing_centerSchedule)) {

            return apiResponse([], 'This center schedule already exists',409);
        }

        if ($centerSchedule->update($data)) {

            return apiResponse(CenterSchedule::find($id),'Center schedule successfull save',200);
        }
        
        return apiResponse([],'Bad request',400);

    }


    public function delete_centerSchedule(Request $request, $id) {

        $center_schedule = CenterSchedule::find($id);
        if(empty($center_schedule)) {

            return apiResponse([], 'not found',404);
        }

        if ($center_schedule->delete()) {

            return apiResponse([], 'success', 201);

        } else {
            return apiResponse([], 'Center schedule detail not deleted', 400); 
        }
    }

    public function get_centerSchedule(Request $request, $id=null) {
        
        if(isset($id)) {
            $center_schedule = CenterSchedule::find($id);
            if(empty($center_schedule)) {

                return apiResponse([], 'not found',404);
            }

            return apiResponse($center_schedule,'success',200);
        }

        $center_schedule = CenterSchedule::all();

        return apiResponse($center_schedule,'success',200);

    }


}




