<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Center;
use App\Models\CenterAdmin;
use App\Models\CentersClaim;
use Illuminate\Support\Facades\Http;
use App\Models\GHLAccessToken;

class AuthController extends Controller
{

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }



    public function  claimCenter(Request $request, $id)
    {

        $validate = Validator::make($request->all(), [
            'first_name' => 'bail|required|string|min:3',
            'last_name' => 'bail|required|string|min:3',
            'email' => $request->custom_email ? 'required|email:rfc,dns|max:255' : 'nullable|email:rfc,dns|max:255',
        ]);
        

        $validation_errors = $validate->errors();

        if ($validate->fails()) {  
            return response()->json(['errors' => error_processor($validate)], 422);

        }  

       //check for center admin 
        
        $center = Center::where('slug', $id)->first(); 
       

        if ($center && $center->centerInfo && $center->centerInfo->email) {
            $response = $this->profileService->claimCenter($request, $id);
            return $response;
        } else {
            return response()->json(['errors' => 'No admin email found'], 400);
        }
        




    }



  



    public function  registerLogin(Request $request, $id)
    {



        $validate = Validator::make($request->all(), [
            'username' => 'bail|required|string|min:3',
            'otp' => 'bail|required|string|min:3',
            'password' => 'required',

        ]);



        if ($validate->fails()) {

            return response()->json(['errors' => error_processor($validate)], 422);
        }


        $vendor = CenterAdmin::where(['email' => $request['username']])->orWhere('username', $request['username'])->first();

        if (!$vendor) {

            return response()->json(['errors' => 'Invalid Username or Email'], 400);
        }


        //check for otp code validity    

        $center = Center::where('slug', $id)->first();

        if (!$center) {

            return response()->json(['errors' => 'center not found'], 400);
        }

        //check for claim center   
        $cliam = CentersClaim::where('center_id', $center->id)->where('verification_code', $request->otp)->first();


        if (!$cliam) {

            return response()->json(['errors' => 'Invalid Code'], 400);
        }


        $data = [
            'email' => $cliam->email,
            'password' => $request->password
        ];


        if (auth('vendor')->attempt($data)) {

            $cliam->verified = true;
            $cliam->save();
            
            $center->claim = true;
            $center->center_admin_id = $vendor->id;
            $center->save();



            return response()->json(['token' => $this->genarate_token($request['email'])], 200);
        } else {


            return response()->json(['errors' => 'Invalid Username or Password'], 400);
        }
    }







    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => error_processor($validator)], 422);
        }


        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];


        if (auth('vendor')->attempt($data)) {

            $token = $this->genarate_token($request['email']);
            $vendor = CenterAdmin::where(['email' => $request['email']])->first();

            $vendor->auth_token = $token;
            $vendor->save();
            return response()->json(['token' => $token, 'vendor' => $vendor], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
            return response()->json([
                'errors' => $errors
            ], 422);
        }
    }



    private function genarate_token($email)
    {
        $token = Str::random(120);
        $is_available = CenterAdmin::where('auth_token', $token)->where('email', '!=', $email)->count();
        if ($is_available) {
            $this->genarate_token($email);
        }
        return $token;
    }
    




    public function  register(Request $request, $id)
    {

      
        $validate = Validator::make($request->all(), [
            'otp' => 'bail|required|string|min:3',
            'email' => 'required|email:rfc,dns|max:255|unique:center_admins',
            'password' => [
                'required', 'string', 'min:8',
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[%!&*$#@|+]/',
                'regex:/[a-z]/',
                'confirmed'
            ],
        ]);

        $validation_errors = $validate->errors();


        if ($validate->fails()) { 
            return response()->json(['errors' => error_processor($validate)], 422);
        }

            //check for otp code validity    

            $center = Center::where('slug', $id)->first();

            if ($center) {

                //check for claim center   
                $cliam = CentersClaim::where('center_id', $center->id)->where('verification_code', $request->otp)->first();

                if ($cliam) {   

                    //create location and ghl user here 

                   $location = $this->ghlCreateLocation($request, $cliam);

                   $user = $this->ghlCreateUser($location->id, $request, $cliam); 


                    $response = $this->profileService->registerCenterProfile($request,  $cliam,  $center, $location, $user);
                    return  $response;

                } else { 

                      return response()->json(['errors' => 'center not found'], 400);
                }
            } else {

                return response()->json(['errors' => 'center not found'], 400);
            }
}   



private function ghlCreateLocation($request, $cliam) {
                    

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.GhlAccessToken()->access_token,
        'Content-Type' => 'application/json',
        'Version' => '2021-07-28',
    ]) 
        ->post('https://services.leadconnectorhq.com/locations/', [
            "name" => $cliam->first_name.' '.$cliam->last_name,
            "companyId" => GhlAccessToken()->companyId,
            "prospectInfo" => [
                "firstName" => $cliam->first_name,
                "lastName" => $cliam->last_name,
                "email" => $request->email,
            ],
            "settings" => [
                "allowDuplicateContact" => false,
                "allowDuplicateOpportunity" => false,
                "allowFacebookNameMerge" => false,
                "disableContactTimezone" => false
            ],
        
        ]);
    
       
      return handleResponse($response,  [$this, 'ghlCreateLocation'], claim: $cliam); 

}  



private function ghlCreateUser($location_id , $request, $cliam) {
     

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.GhlAccessToken()->access_token,
            'Content-Type' => 'application/json',
            'Version' => '2021-07-28',
        ])
            ->post('https://services.leadconnectorhq.com/users/', [
                "companyId" => GhlAccessToken()->companyId,
                "firstName" => $cliam->first_name,
                "lastName" => $cliam->last_name,
                "email" => $request->email,
                "password" => $request->password, 
                "type" => "account",
                "role" => "admin",
                "locationIds" => [
                    $location_id
                ],
                "permissions" => [
                    "campaignsEnabled" => true,
                    "campaignsReadOnly" => false,
                    "contactsEnabled" => true,
                    "workflowsEnabled" => true,
                    "workflowsReadOnly" => true,
                    "triggersEnabled" => true,
                    "funnelsEnabled" => true,
                    "websitesEnabled" => false,
                    "opportunitiesEnabled" => true,
                    "dashboardStatsEnabled" => true,
                    "bulkRequestsEnabled" => true,
                    "appointmentsEnabled" => true,
                    "reviewsEnabled" => true,
                    "onlineListingsEnabled" => true,
                    "phoneCallEnabled" => true,
                    "conversationsEnabled" => true,
                    "assignedDataOnly" => false,
                    "adwordsReportingEnabled" => false,
                    "membershipEnabled" => false,
                    "facebookAdsReportingEnabled" => false,
                    "attributionsReportingEnabled" => false,
                    "settingsEnabled" => true,
                    "tagsEnabled" => true,
                    "leadValueEnabled" => true,
                    "marketingEnabled" => true,
                    "agentReportingEnabled" => true,
                    "botService" => false,
                    "socialPlanner" => true,
                    "bloggingEnabled" => true,
                    "invoiceEnabled" => true,
                    "affiliateManagerEnabled" => true,
                    "contentAiEnabled" => true,
                    "refundsEnabled" => true,
                    "recordPaymentEnabled" => true,
                    "cancelSubscriptionEnabled" => true,
                    "paymentsEnabled" => true
                ],
               
            ]);

            return handleResponse($response,  [$this, 'ghlCreateUser'], claim: $cliam); 
    

}

 

public function getCenterAdmin(Request $request){
  //get the center admin detail by location id and email; 
 
//    $admin = CenterAdmin::where('email', $request->email)->where('location_id', $request->location_id)->first();
      $admin = CenterAdmin::where('location_id', $request->location_id)->first();

     return apiResponse($admin, 'success', 200);  

}




}
