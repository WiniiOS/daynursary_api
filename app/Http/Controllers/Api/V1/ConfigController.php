<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Service;
use App\Models\JobType;
use App\Models\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
 

class ConfigController extends Controller
{  
     

   
   public function getConfig(){

     
    //find all services
     $services = Service::all();

     //find all jobs types  

     $jobtype = JobType::all();

     $data = [
       'services' => $services,
       'jobtype' => $jobtype
     ]; 


     return apiResponse($data, 'App Configs', 200);  
     
   }
   

   public function getAllFeatures(){

     $features = Feature::where('for','childcare')->get();
     return apiResponse($features, 'Features', 200);
   }


  
}
