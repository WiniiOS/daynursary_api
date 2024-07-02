<?php 
namespace App\Services;
use App\Models\City;
use App\Models\User;
use App\Models\State;
use Ramsey\Uuid\Uuid;
use App\Models\Center;
use App\Models\Country;
use App\Models\BookATour;
use App\Models\ParentChild;
use App\Traits\Transformer;

use App\Models\CenterReview;
use Illuminate\Support\Facades\DB;use App\Models\ParentProfile;
use App\Models\ChildCareApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\CenterResource;
use App\Http\Resources\V1\ApplicationResource;
use App\Models\UserFavoriteCenter;

class CenterService
{   
   

 public function popularCenters($limit=10 ,$offset=1) { 
   
    $centers = Center::orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $offset); 
     
    $data =  [
        'total_size' => $centers->total(),
        'limit' => $limit,
        'offset' => $offset,
        'centers' =>CenterResource::collection($centers)
    ];
    
    return response()->json($data, 200);

 }


 public function featuredCenters($limit=10 ,$offset=1) { 
   
    $centers = Center::orderBy('created_at', 'asc')->paginate($limit, ['*'], 'page', $offset); 
     
    $data =  [
        'total_size' => $centers->total(),
        'limit' => $limit,
        'offset' => $offset,
        'centers' =>CenterResource::collection($centers)
    ];
    
    return response()->json($data, 200);

 }   


 public function searchCenters($search) {  

    if (empty($search)) {
        
        $centers = Center::orderBy('created_at', 'desc')->get();
    }else{
        $centers = Center::where('name','like','%'.$search.'%')->get();
    }

   


    

    $data =  [
        'centers' =>CenterResource::collection($centers)
    ];
    
    return response()->json($data, 200);

 }


 public function getCenter($slug) {
    $center = Center::with('centerInfo')->where('slug', $slug)->first();

    if (!$center) {
        return response()->json(['error' => 'Center not found'], 404);
    }

    $data =  [
        'center' => new CenterResource($center),
    ];

    return response()->json($data, 200);
}


public function getCenterDetail($slug) {
    $center = Center::where('slug', $slug)->first();

    if (!$center) {
        return response()->json(['error' => 'Center not found'], 404);
    }

    $data =  [
        'center' => new CenterResource($center),
    ];

    return response()->json($data, 200);
}



public function childCaredApplication($center , $child) {

    $user = Auth::user()->profile;

    $otherparent = $user->subProfiles;
    //check if there is any subprofiles;
    if ($otherparent->count() > 0) {

        $parent = $otherparent->first()->id;
    }else{

        $parent = null;
    }
    
    //create a childcare application.
    //loop through the child data

      
    //   let me get a new array of services
    $services_ids = [];
    foreach ($child['services'] as $service) {
        
    $services_ids[]= $service['id'];
    }

//  return $services_ids;

    $childapplication= new ChildCareApplication();

    $childapplication->center_id = $center->id;
    $childapplication->parent_id = $user->id; 
    $childapplication->additional_parent_id = $parent;
    $childapplication->child_id = $child['child']['id'];
    $childapplication->days = json_encode($child['days']);
    $childapplication->status = 'submitted';
    $childapplication->date = $child['date'] ;
    $childapplication->save();  
    
    //attach application to application_center_services table

    $childapplication->centerServices()->attach($services_ids); 


    return  new ApplicationResource($childapplication); 

}


    public function getApplications($center){
        $applications = ChildCareApplication::where('center_id', $center->id)->get();
        return  ApplicationResource::collection($applications); 
    }

    public function getReviews($center) {

        $data = $center->reviews()->paginate(5); 
       
         
        $reviewsSummary = DB::table('center_reviews')
        ->select(
            'rating',
            DB::raw('COUNT(*) as totalReviews'),
            DB::raw('AVG(rating) as averageRating')
        )
        ->where('center_id', $center->id)
        ->groupBy('rating')
        ->orderBy('rating')
        ->get();

    // Initialize the result object
    $formattedSummary = [
        'totalReviews' => 0,
        'averageRating' => 0,
    ];

    for ($i = 1; $i <= 5; $i++) {
        $formattedSummary["star{$i}"] = [
            'totalReviews' => 0,
        ];
    }

    // Populate the result object with database values
    foreach ($reviewsSummary as $summary) {
        $formattedSummary['totalReviews'] += $summary->totalReviews;
        $formattedSummary['averageRating'] += $summary->rating * $summary->totalReviews;

        $formattedSummary["star{$summary->rating}"]['totalReviews'] = $summary->totalReviews;
    }

    // Calculate the overall average rating on 5
    if ($formattedSummary['totalReviews'] > 0) {
        $formattedSummary['averageRating'] /= $formattedSummary['totalReviews'];
        $formattedSummary['averageRating'] = round($formattedSummary['averageRating'], 2);
    }

        return ['data' => $data, 'summary' => $formattedSummary];
     

    }

    public function addReview($data) {

        $responseData = CenterReview::create($data);
        return response()->json($responseData, 200);

    }

    public function addBookATour($data) {

        $responseData = BookATour::create($data);
        return response()->json($responseData, 200);

    }
    

    // Get a review by user ID and Center ID
    public function getReview($user_id) {

        $data = CenterReview::where('user_id', $user_id)->first();
        // $flights = Flight::where('destination', 'Paris')->get();
 
        if (!$data) {
            return response()->json(['error' => 'The review was not found'], 404);
        }
        return response()->json($data, 200);
    }

    public function getReviewsByRating($rating) {

        // $data = CenterReview::where('rating', $rating)->where('user_id', $userId)->get();
        $data = CenterReview::where('rating', $rating)->get();

        if (!$data) {
            return response()->json(['error' => 'The review was not found'], 404);
        }

        return response()->json($data, 200);
    }

    public function addFavouriteCenter($data){
        try{ 
    
            $favcenter = UserFavoriteCenter::create($data);
            if($favcenter){
                return apiResponse([],'success', 201); 
            }
                return apiResponse([],'an unexpected error occured', 201); 
    
        }catch (\Exception $exception) {
            return response()->json(['errors' => 'An error occurred ' . $exception], 500);
        }
    
    }
    
    public function deleteFavCenter($id){
        try{
                $fav = UserFavoriteCenter::findOrFail($id);
            
                $fav->delete();
                return apiResponse([ 'message' => 'ceneter updated successfully'],'succes', 200);
            
                
            }catch(ModelNotFoundException $exception){
                info($exception);
                return apiResponse([ 'errors' => 'fav not found', 'message' => 'fav not found'],'not found', 400);
                
                
            }catch (\Exception $exception) {
                info($exception);
                return apiResponse([ 'errors' => 'An error occurred', 'message' => 'An error occurred'],'error', 500); 
            }
        }


    public function getBookATour() {

        $responseData = BookATour::all();
        return response()->json($responseData, 200);

    }
    


}
