<?php

namespace App\Services;

use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\ParentProfile;
use App\Models\ParentChild;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Models\Center;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\CenterResource;
use App\Models\Job;
use App\Models\JobRole;
use App\Models\Service;
use App\Traits\Transformer;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

class SearchService
{


    public function SearchSuggestions($request, $limit = 10, $offset = 1)
    {


        $query = $request->keyword;

        // Search for postcodes from centers  
        $postcodes = Center::where('post_code', 'like', "%$query%")
            ->select('post_code as name', 'post_code')
            ->get();

        // Format the results
        $formattedPostcodes = $postcodes->map(function ($postcode) {
            return [
                'name' => $postcode->name,
                'postCode' => $postcode->post_code,
                'type' => 'postcode',
            ];
        });




        // Search for cities
        $cities = City::where('name', 'like', "%$query%")
            ->select('name as name', 'id', 'state_id')
            ->get();



        $formattedCities = $cities->map(function ($city) {
            return [
                'name' => $city->name,
                'id' => $city->id,
                'city' => $city->name,
                'state' => $city->state->name,
                'country' => $city->state->country->name,
                'type' => 'city',
            ];
        });




        // Search for centres
        $centres = Center::where('name', 'like', "%$query%")
            ->orWhere('address', 'like', "%$query%")->offset($offset)
            ->limit($limit)->paginate($limit);





        $formattedCenters = $centres->map(function ($cent) {

            return [
                'id' => $cent->id,
                'slug' => $cent->slug,
                'name' => $cent->name,
                'post_code' => $cent->post_code,
                'address' => $cent->address,
                'city' => $cent->city,
                'country' => $cent->country,
                "type" => 'center',
            ];
        });



        //search for jobs and jobs type.   


        $jobs = Job::where('title', 'like', "%$query%")
            ->orWhere('service_to_render', 'like', "%$query%")->offset($offset)
            ->limit($limit)
            ->select('id', 'title', 'slug',)
            ->paginate($limit);


        $formattedJobs = $jobs->map(function ($job) {

            return [
                'id' => $job->id,
                'slug' => $job->slug,
                'name' => $job->title,

                "type" => 'job',
            ];
        });





        $jobs_role = JobRole::where('name', 'like', "%$query%")
            ->limit($limit)
            ->select('id', 'name')
            ->paginate($limit);


        $formattedJobsRole = $jobs_role->map(function ($jobrole) {

            return [
                'id' => $jobrole->id,
                'name' => $jobrole->name,
                "type" => 'jobrole',
            ];
        });







        // Combine results into a single array
        $results = array_merge($formattedPostcodes->toArray(), $formattedCities->toArray(),  $formattedCenters->toArray(), $formattedJobs->toArray(), $formattedJobsRole->toArray());


        $data =  [
            'total' => count($results),
            'results' => $results,

        ];

        return response()->json($data, 200);
    }




    public function SearchCenters($request, $limit = 5, $offset = 1)
    {


        // $query = $request->input('query', '');
        // $postcode = $request->input('postcode', '');
        // $city = $request->input('city', '');
        // $distance = $request->input('distance', 50); // Default distance in kilometers

        // $priceRange = $request->input('price', [0, 1000]); // Assuming price is a numeric field
        // $serviceFilter = $request->input('service', []);
        // $type = $request->input('type');
        // $city_id = $request->input('city_id');
        // $filters = $request->input('filters');

        //  // Extract individual filter values
        //  $filters = json_decode($request->input('filters'), true);
        //  $selectedServices = $filters['selectedServices'] ?? [];
        //  $ratingRange = $filters['ratingRange'] ?? [];

        $searchTerm = $request->input('q');
        $searchType = $request->input('type');
        $cityId = $request->input('city_id');

        $userLatitude = $request->input('lat') ;
        $userLongitude = $request->input('lng');

        // Decode JSON filters from the request
        $filters = json_decode($request->input('filters'), true);

        // Extract individual filter values
        $selectedServices = $filters['selectedServices'] ?? [];
        $ratingRange = $filters['ratingRange'] ?? [];

        $orderBy = $request->input('sorting') ?? 'featured';



        $results = Center::search($searchTerm, $searchType, $cityId, $ratingRange, $selectedServices);
        
        $allServices = Service::all();
        $summary = $this->getSearchSummary($results->get(), $allServices);
        $highestRatedCenter = $this->getHighestRatedCenter($results->get());
        $reviewsSummary = $this->getReviewsSummary($results->get()); 
       
        //sorting options
        if ($orderBy == 'featured') {
            $results = $results->orderBy('featured', 'desc');
        } elseif ($orderBy == 'rating') {
            $results = $results->orderByDesc('rating');
        } elseif ($orderBy == 'distance') {
            $results = $results
                ->select(DB::raw('*, (6371 * acos(cos(radians(' . $userLatitude . ')) * cos(radians(lat)) * cos(radians(lng) - radians(' . $userLongitude . ')) + sin(radians(' . $userLatitude . ')) * sin(radians(lat)))) as distance'))
                ->orderBy($orderBy) ;
        } elseif ($orderBy == 'price') {

            $results = $results
                ->with(['services' => function ($query) use ($selectedServices) {
                    $query->whereIn('service_id', $selectedServices);
                }])
                ->addSelect([
                    'price' => Service::select('price_per_day')
                        ->whereIn('service_id', $selectedServices)
                        ->whereColumn('center_id', 'centers.id')
                        ->orderBy('price_per_day', 'asc')
                        ->limit(1)
                ])
                ->orderBy($orderBy)
                ;
        } elseif ($orderBy == 'newest_approved' || $orderBy == 'oldest_approved') {
            $results = $results
                ->orderBy($orderBy, ($orderBy == 'approved_date' && $request->input('sort') == 'oldest_approved') ? 'asc' : 'desc');
                
        }
        
        
       
        $limit = 4;
        
        $centers = $results->paginate($limit); 
        $centers->data =CenterResource::collection($centers);
       
        
        $data =  [
            'centers' => $centers,
            'summary' => $summary,
            'highestRatedCenter' => $highestRatedCenter,
            'reviewsSummary' => $reviewsSummary,
        ];
       

        return $data;


    }





    private function getSearchSummary($results, $allServices)
    {
        $summary = [];

        // Initialize the summary with all services and zero counts and total cost
        foreach ($allServices as $service) {
            $summary[$service->name] = [
                'count' => 0,
                'totalCost' => 0,
            ];
        }

        foreach ($results as $center) { 

            $primaryService = $center->primaryService;
            if($primaryService){   

                $serviceName = $primaryService->service->name;
                $costPerDay = $service->price_per_day;

               $summary[$serviceName]['count']++;
               $summary[$serviceName]['totalCost'] += $costPerDay;
              
           }

            // foreach ($center->services as $service) {
            //     $serviceName = $service->name;
            //     $costPerDay = $service->pivot->price_per_day;

            //     $summary[$serviceName]['count']++;
            //     $summary[$serviceName]['totalCost'] += $costPerDay;
            // }

        }

        // Calculate average cost per day
        foreach ($summary as $serviceName => &$data) {
            if ($data['count'] > 0) {
                $data['averageCostPerDay'] = $data['totalCost'] / $data['count'];
            } else {
                $data['averageCostPerDay'] = 0;
            }
        }

        return $summary;
    }



    private function getHighestRatedCenter($results)
    {
        $highestRatedCenter = null;
        $highestRating = 0;

        foreach ($results as $center) {
            $centerRating = $center->rating;

            if ($centerRating > $highestRating) {
                $highestRating = $centerRating;
                $highestRatedCenter = $center;
            }
        }

        return $highestRatedCenter;
    }



    private function getReviewsSummary($results)
    {
        $totalReviews = 0;
        $totalRating = 0;

        foreach ($results as $center) {
            $totalReviews += $center->reviews->count();
            $totalRating += $center->reviews->avg('rating');
        }

        $averageRating = ($totalReviews > 0) ? ($totalRating / $totalReviews) : 0;

        return [
            'totalReviews' => $totalReviews,
            'averageRating' => $averageRating,
        ];
    }


    public function SearchCentersSummary($request)
    {
    }




    public function SearchJobs($request, $limit = 5, $offset = 1)
    {


        if (empty($search)) {

            $jobs = Job::orderBy('created_at', 'desc')->with(['center'])->paginate($limit);
        } else {
            $jobs = Job::where('name', 'like', '%' . $search . '%')->with(['center'])->paginate($limit);
        }


        return $jobs;
    }
}
