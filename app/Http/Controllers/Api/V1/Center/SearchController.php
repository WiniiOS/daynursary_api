<?php    
 
 namespace App\Http\Controllers\Api\V1\Center; 

 use App\Http\Controllers\Controller;
 use App\Models\Center;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Validation\Rule;
 use App\Services\SearchService;   
 use App\Traits\Transformer;  


 class SearchController extends Controller
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



    public function SearchCenters(Request $request)
    {
      
        $data = $this->searchService->SearchCenters($request, $request->limit, $request->offset);
        
      

        $searchValues = array(
            'keyword' => $request->keyword,
            'state' => $request->state,
            'city' => $request->city,
            'postcode' => $request->postcode,
            'limit' => $request->limit,
            'offset' => $request->offset,
            'type' => $request->type,
            'filters' => $request->filters

        );
         

      
    
     //  $meta = Transformer::transformCollection($data);
       $meta = [];
       $message = 'All listing Successfully.';
     
       
        return apiResponse($data, $message, 200, $meta, $searchValues);
      
    }
    

    public function SearchCentersSummary(Request $request)
    {
      
        $data = $this->searchService->SearchCentersSummary($request);

      
     $data = [];
      
   
       $message = 'Summary Successfully.';
     
       
        return apiResponse($data, $message, 200);
      
    }
    



}




