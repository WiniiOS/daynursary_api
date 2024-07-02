<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

use App\Http\Exceptions;
use App\Models\GHLAccessToken;

use http\Exception\InvalidArgumentException;

class SocialiteController extends Controller
{
    protected $providers = ["google", "facebook"];

    public function redirect(Request $request)
    {

        $provider = $request->provider;

        if (in_array($provider, $this->providers)) {
            return Socialite::driver($provider)->redirect();
        }
        abort(404);
    }

    public function callback(Request $request)
    {
        $provider = $request->provider;

        if (in_array($provider, $this->providers)) {
            $data = Socialite::driver($request->provider)->user();

            $id = $data->getId();
            $name = $data->getName();
            $nickname = $data->getNickname();
            $email = $data->getEmail();
            $avatar = $data->getAvatar();

            $user = User::where("email", $email)->first();
            $password = Hash::make($this->randomPassword());
            $provider_attribute = $this->getProvider($provider);

            if (!isset($user)) {
                $user = User::create([
                    'first_name' => $name,
                    'uuid' => Uuid::uuid4(),
                    'email' => $email,
                    'password' => $password,
                    "$provider_attribute" => $provider,
                ]);
            }
            auth()->login($user);
        }
        abort(404);
    }

    public function getProvider($provider)
    {
        $attribute_name = "facebook_id";
        if ($provider == "google") $attribute_name = "google_id";
        return $attribute_name;
    }

    public function ghlConnect()
    {
        return view('ghl');
    }

    public function ghlCallback(Request $request)
    {

       
        $clientId = env('clientId');
        $clientSecret = env('clientSecred');
        $redirect_url = url('/callback');


        try {


            $response = Http::asForm()->post('https://api.msgsndr.com/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirect_url,
                'code' => $request->code,
            ]);
             
            $status = $response->status();
            $response = $response->json();

            // Update or create the data in the database 
         
           if($status==200){

            GHLAccessToken::updateOrCreate([], $response); 

            return response()->json(['status' => 'success', 'message' => 'GHL Access Token Generated', 'data' => $response], $status);
               
           }else{

               return response()->json(['status' => 'error', 'message' => $response], $status);
           }

            
        } catch (\Exception  $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    public function ghlCreateLocation(Request $request){
                    

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.GhlAccessToken()->access_token,
                'Content-Type' => 'application/json',
                'Version' => '2021-07-28',
            ]) 
                ->post('https://services.leadconnectorhq.com/locations/', [
                    "name" => "Blezour Blec D",
                    "phone" => "+1410039940",
                    "companyId" => GhlAccessToken()->companyId,
                    "address" => "4th fleet street",
                    "city" => "New York",
                    "state" => "Illinois",
                    "country" => "AF",
                    "postalCode" => "567654",
                    "website" => "https://yourwebsite.com",
                    "timezone" => "US/Central",
                    "prospectInfo" => [
                        "firstName" => "Blezour",
                        "lastName" => "Blec",
                        "email" => "john.doe@mail.com"
                    ],
                    "settings" => [
                        "allowDuplicateContact" => false,
                        "allowDuplicateOpportunity" => false,
                        "allowFacebookNameMerge" => false,
                        "disableContactTimezone" => false
                    ],
                
                ]);
            
               
                $processed_response = handleResponse($response, 'ghlCreateLocation'); 
                $status = $response->status();
                $res = $response->json();
               
              
                if( $processed_response && $status==200){ 
                   
                    //call a function to create a user here
                    $this->createUser( $res['id'], $request );

                } 
                

           // dd($response->json());
            // $response->body();

            //sample results  


            //             array:18 [▼ // app\Http\Controllers\Api\V1\Auth\SocialiteController.php:152
            // "id" => "nybWUV2MLwZOmVnw2dl7"
            // "companyId" => "ech4liDfaCQLggU7025R"
            // "name" => "Blezour Blec D"
            // "address" => "4th fleet street"
            // "city" => "New York"
            // "state" => "Illinois"
            // "country" => "AF"
            // "postalCode" => "567654"
            // "website" => "https://yourwebsite.com"
            // "timezone" => "US/Central"
            // "firstName" => "Blezour"
            // "lastName" => "Blec"
            // "email" => "john.doe@mail.com"
            // "phone" => "+1410039940"
            // "social" => array:11 [▶]
            // "settings" => array:5 [▶]
            // "dateAdded" => "2024-01-08T11:48:54.346Z"
            // "traceId" => "fcbd17eb-82d3-4b5b-ad18-34dc9d13cda8"
            // ]



    }  


    public function ghlCreateUser($location_id , $request){
         

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.GhlAccessToken()->access_token,
                'Content-Type' => 'application/json',
                'Version' => '2021-07-28',
            ])
                ->post('https://services.leadconnectorhq.com/users/', [
                    "companyId" => GhlAccessToken()->companyId,
                    "firstName" => "John",
                    "lastName" => "Deo",
                    "email" => "john@deo.com",
                    "password" => "673610204Blec_@", 
                    "phone" => "+18832327657",
                    "type" => "account",
                    "role" => "admin",
                    "locationIds" => [
                        "nybWUV2MLwZOmVnw2dl7"
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

                $processed_response = handleResponse($response, 'ghlCreateUser'); 
                $status = $response->status();
                $res = $response->json();
               
              
                if( $processed_response && $status==200){ 
                   
                    // create a Daynursary user now;


                } 
                
               
 

    }
    
}
