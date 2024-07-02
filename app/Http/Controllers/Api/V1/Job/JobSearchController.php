<?php    
 
 namespace App\Http\Controllers\Api\V1\Job; 

 use App\Http\Controllers\Controller;
 use App\Models\Center;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Validation\Rule;
 use App\Services\SearchService;   
 use App\Traits\Transformer;  


 class JobSearchController extends Controller
 {
    
    protected $searchService; 

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }


    public function SearchSuggestions(Request $request)
    {
        
        $response = $this->searchService->SearchSuggestions($request, $request->limit, $request->offset);
        return  $response;
    }  



    public function SearchJobs(Request $request)
    {
      
        $data = $this->searchService->SearchJobs($request, $request->limit, $request->offset);

        $searchValues = array(
            'keyword' => $request->keyword,
            'state' => $request->state,
            'city' => $request->city,
        );
         

      
    
       $meta = Transformer::transformCollection($data);
       $message = 'All listing Successfully.';
     
       
        return apiResponse($data, $message, 200, $meta, $searchValues);
      
    }
    
    



}




