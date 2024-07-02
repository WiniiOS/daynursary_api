<?php    
 
 namespace App\Http\Controllers\Api\V1\Center; 

 use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CenterFavouriteResource;
use App\Http\Resources\V1\CenterResource;
use App\Models\Center;
use App\Models\UserFavoriteCenter;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Validation\Rule;
 use App\Services\CenterService;
 use App\Models\Service;
 use App\Models\CenterService as ServiceCenter;
 use Illuminate\Support\Facades\Auth;
 
 


 class CenterController extends Controller
 {
    
    protected $centerService;

    public function __construct(CenterService $centerService)
    {
        $this->centerService = $centerService;
    }


    public function getPopularCenters()
    {
        $response = $this->centerService->popularCenters();
        return  $response;
    }
    
    

    public function getFeaturedCenters()
    {
        $response = $this->centerService->featuredCenters();
        return  $response;
    } 



    public function  SearchCenters(Request $request) {

        $response = $this->centerService->searchCenters($request->keyword);
        return  $response;

    } 


    public function getCenter($slug){

        $response = $this->centerService->getCenter($slug);
        return  $response;
    } 



    public function centerDetails($slug){
        $response = $this->centerService->getCenterDetail($slug);
        return  $response; 
    }
    

    public function chilCaredApplication(Request $request, $slug){

        $childrenData = $request->input('data');

        if (!is_array($childrenData) || count($childrenData) === 0) {
            return response()->json(['error' => 'Invalid data format.'], 422);
        }

        // $validator = Validator::make($childrenData, [
        //     'data.*.child_id' => 'required|exists:children,id',
        //     'data.*.application_date' => 'required|date', 
        //     'data.*.center_id' => 'required|exists:centers,id',
        // ]);
      
        $center= Center::where('slug', $slug)->first();  
         
        $applications = [];
        foreach ($childrenData as $child) {

         $applic = $this->centerService->childCaredApplication($center, $child); 

         //add to applications list   
         $applications[] = $applic;


        }

        
        return apiResponse($applications, 'Applications created', 201); 
    } 


    public function applications($slug){ 

        $center= Center::where('slug', $slug)->first(); 
        $response = $this->centerService->getApplications($center);
        return  $response;  
    }

    public function getSingle($id)
    {
        $response = $this->centerService->getReview($id);
        return  $response;
    }

    public function get_reviews( $slug )
    {   
        $center= Center::where('slug', $slug)->first();
        if( !$center ){
            return response()->json(['error' => 'Center not found'], 404);
        }

        $response = $this->centerService->getReviews($center);
       
        return apiResponse($response, 'Reviews', 200); 
        
    }

    public function add_review(Request $request)
    {  
       

        $validate = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5', 
            'review' => 'required|string',
            'display_name' => 'required|string',
        ]);
    
        if ($validate->fails()) {
           
            return response()->json(['errors' => error_processor($validate)], 422);
        } 
        
       

        $image = Auth::User()->profile->image ? env("AWS_URL").'/'.Auth::User()->profile->image:'';
       
        $data = [
            'user_id' => Auth::user()->id,
            'center_id' => $request->center_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'source' => 'daynursary',
            'avatar' =>  $image,
            'display_name' => $request->display_name,
        ];

        $response = $this->centerService->addReview($data);

        return  $response;
    }

    public function updateFavouriteCenter (Request $request){
        $data = $request->all();
        $validated = Validator::make($data, [
            'center_id' => 'required|numeric|distinct|exists:App\Models\Center,id',
        ]);

        if ($validated->fails()) {

            return apiResponse( error_processor($validated), $validated->errors()->first(), 422);
        }

        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([],'User  not found or user not logged',404);
        }

        $existingFavorite = UserFavoriteCenter::where('user_id', $user->id)
                ->where('center_id', $data['center_id'])
                ->first();

        if ($existingFavorite) {
            //    $response = $this->jobService->deleteView($data['job_id']);
            $existingFavorite->delete();
            return apiResponse([],'success',201);
        }
        $id = $user->id;
        $data = array_merge($data, ['user_id' => $id]);

        $response = $this->centerService->addFavouriteCenter($data);

        return $response;


        // 'user_id', 'center_id',

    }

    public function getFavCenters(){
        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([],'User  not found or user not logged',404);
        }

        $favoriteCenters = $user->FavouriteCenters()->get();
        //->with('center')
        $data = CenterFavouriteResource::collection($favoriteCenters);

        return apiResponse($data,'success',200);
    }

    public function getAll(){
        try{
            $centers = Center::all();
            $data = CenterResource::collection($centers);
            return apiResponse($data,'success',200);
        }
        catch(\Exception $exception){
            info($exception);
            return apiResponse([],'An unexpected error occured',500);

        }
       
    }

    public function get_book_a_tours(){
        try{

            $response = $this->centerService->getBookATour();

            return $response;
        }
        catch(\Exception $exception){
            info($exception);
            return apiResponse([],'An unexpected error occured',500);
        }
       
    }

    
    public function add_book_a_tour(Request $request)
    {
        $data = [
            'user_id' => $request->user_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'telephone' => $request->telephone,
            'address' => $request->address,
            'email' => $request->email,
            'totalVisitors' => $request->totalVisitors,
            'child_first_name' => $request->child_first_name,
            'child_last_name' => $request->child_last_name,
            'childs' => $request->childs,
            'message' => $request->message,
            'choosed_time' => $request->time,
            'choosed_date' => $request->date
        ];

        $response = $this->centerService->addBookATour($data);

        return  $response;
    } 

}




