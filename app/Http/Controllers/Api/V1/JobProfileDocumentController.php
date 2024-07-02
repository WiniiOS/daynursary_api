<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParentProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\JobProfile;
use App\Models\JobProfileDocument;
use App\Services\JobService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Laravel\Ui\Presets\React;


class JobProfileDocumentController extends Controller
{

    protected $JobService;
    public function __construct(JobService $JobService)
    {
        $this->JobService = $JobService;
    }



    public function manage_document_updates(Request $request){
        $formData = $request->all();
        $formObject =json_decode($formData['formObject'],true);
        info($formObject); 

        
        //looping through the formObject to ressolve it operation
        try{
            foreach($formObject as $key => $item){
                info($key);
                $action = $item['action'];
                $response=null;
                if(isset($formData[$key])){
                    info('here');
                    $file = $formData[$key];
                    switch($action){
                        case 'create':
                            info('here create');
                            if(isset($item['type']) && $item['type']==='link'){
                                $response = $this->createDocument($formObject['video']['name'],$key,$vtype='link');
                            }else{
                                $response = $this->createDocument($file,$key);
                            }
                           
                            break;
                        case 'update':
                            info('here update');
                            $id =  $item['id'];
                            if(isset($item['type']) && $item['type']==='link'){
                                info('file'.$formObject['video']['name']);
                                $response = $this->updateDocument($formObject['video']['name'],$key,$id,$vtype='link');
                            }else{
                                info('out');
                                $response = $this->updateDocument($file,$key,$id);
                            }
                            break;
                        case 'delete':
                            $id =  $item['id'];
                            $response = $this->delete_document($id);
                            info('here delete');
                            break;
                        case '':
                            break;
                        default:
                            //$response=null;
                            break;
                    }

                }
                info($response);
                if ($response && $response !== 'success') {
    
                    return apiResponse([],$response , 200);
                }
            
            }

            return apiResponse([], 'success', 200);


        }
        catch(\Exception $exception){
            info($exception);
            return apiResponse([], 'an unexpected error occured', 200);
        }
    }

    public function createDocument($file,$kind,$vtype='') {

        if($vtype==='link'){
            $user = Auth::user();
            if (empty($user)) {
                return 'User  not found or user not logged'; 
            }
            $job_profile = $user->jobProfile;
            if (empty($job_profile)) {
                return 'Job profile  not found';
            }
            info($file);

            $data = [
                'name' => $file,
                'type' => 'link',
                'link' => $file,
                'kind' => $kind,
                'job_profile_id' => $job_profile->id
            ];

           $document = JobProfileDocument::create($data);

        }
       
        switch($kind){
            case 'video':
                $validator = Validator::make(['file' => $file], [
                    'file' => 'required|file|mimes:mp4,mov|max:25600',
                ]);
                break;
            case 'resume':
                $validator = Validator::make(['file' => $file], [
                    'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
                ]);
                break;
            case 'cover_letter':
                $validator = Validator::make(['file' => $file], [
                    'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
                ]);
                break;
            default:
                $validator = null;
                break;
        }

        if($validator && $validator->fails()){
            info($validator->errors()->first());
            return $validator->errors()->first(); 
        }

        $user = Auth::user();
        if (empty($user)) {
            return 'User  not found or user not logged'; 
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return 'Job profile  not found';
        }
       
            $name = $file->getClientOriginalName();
            $type = $file->getClientMimeType();

            $existing_document = JobProfileDocument::where('name', $name)->where('kind', $kind)->first();
            if (!empty($existing_document)) {
                return  $kind.': '.'this document already exists for this job profile '; 
            }

            $file_path = storeFile($file);

            $link = env("AWS_URL").'/'.$file_path;
            $data = [
                'name' => $name,
                'type' => $type,
                'link' => $link,
                'kind' => $kind,
                'job_profile_id' => $job_profile->id
            ];

           $document = JobProfileDocument::create($data);

        return  'success';

    }


    // update existing profile document
    public function updateDocument($file,$kind, $id,$vtype='') {
        info('update');
        if($vtype==='link'){
            
            $jobProfileDocument = JobProfileDocument::find($id);
            if(empty($jobProfileDocument)) {
                return $kind.': '.'profile document not found';
            }

            $user = Auth::user();
            if (empty($user)) {
                return 'User  not found or user not logged'; 
            }
            $job_profile = $user->jobProfile;
            if (empty($job_profile)) {
                return 'Job profile  not found';
            }

            $profile_document = JobProfileDocument::find($id);
            if(empty($profile_document)) {
                return 'profile document not found'; 
            }
    
            $existing_document = JobProfileDocument::where('kind', $kind)
                ->where('job_profile_id', $job_profile->id)
                ->where('id', '<>', $id)->first();
            if (!empty($existing_document)) {
                return $kind.': '.'this document already exists for this job profile '; 
            }

            //file type = link
            if($profile_document->type !== 'link'){
                
                try{
                    $old_path = parse_url($profile_document->link)['path'];
                    $old_path = ltrim($old_path, "/");
                    delete($old_path);
                }catch(\Exception $exception){
                    info($exception);
                    return  $kind.': '.'document not updated';
                }
            } 

            info($file);
            
            
            $data = [
                'name' => $file,
                'type' => 'link',
                'kind'=>$kind,
                'link' => $file,
                'job_profile_id' => $job_profile->id
            ];
    
            if ($profile_document->update($data)) {
            
                return 'success';
            }
    

        }

        switch($kind){
            case 'video':
                $validator = Validator::make(['file' => $file], [
                    'file' => 'required|file|mimes:mp4,mov|max:25600',
                ]);
                break;
            case 'resume':
                $validator = Validator::make(['file' => $file], [
                    'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
                ]);
                break;
            case 'cover_letter':
                $validator = Validator::make(['file' => $file], [
                    'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
                ]);
                break;
            default:
                $validator = null;
                break;
        }

        if($validator && $validator->fails()){
            
            info($validator->errors()->first());
            return $validator->errors()->first(); 
        }

        $jobProfileDocument = JobProfileDocument::find($id);
        if(empty($jobProfileDocument)) {
            return $kind.': '.'profile document not found';
        }

        $user = Auth::user();
        if (empty($user)) {
            return 'User  not found or user not logged'; 
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return 'Job profile  not found';
        }


        //$files = $request->file();

        $profile_document = JobProfileDocument::find($id);
        if(empty($profile_document)) {
            return 'profile document not found'; 
        }
        $name = $file->getClientOriginalName();
        $type = $file->getClientMimeType();

        $existing_document = JobProfileDocument::where('name', $name)->where('kind', $kind)
            ->where('job_profile_id', $job_profile->id)
            ->where('id', '<>', $id)->first();
        if (!empty($existing_document)) {
            return $kind.': '.'this document already exists for this job profile '; 
        }

        $file_path = storeFile($file);

        if($file_path){
            
            $old_path = parse_url($profile_document->link)['path'];
            $old_path = ltrim($old_path, "/");
            delete($old_path);
        } else {
            return $kind.': '.'document not updated'; 
        }

        $link = env('AWS_URL').'/'.$file_path;
        $data = [
            'name' => $name,
            'type' => $type,
            'kind'=>$kind,
            'link' => $link,
            'job_profile_id' => $job_profile->id
        ];

        if ($profile_document->update($data)) {
        
            return 'success';
        }

    }

    //get all  profile_documents and get one by id
    public function profile_document(Request $request, $id=null) {

        if (isset($id)) {

            $profile_document = JobProfileDocument::where('id', $id)->get();
            if ($profile_document) {

                return apiResponse($profile_document,  'success', 201);

            } else {
                return apiResponse([], 'Profile document not found', 404); 
            }  
        }
        $user = Auth::user();
        if (empty($user)) {
            return apiResponse([], 'User  not found or user not logged', 404); 
        }
        $job_profile = $user->jobProfile;
        if ($job_profile) {
            $profile_documents = JobProfileDocument::where('job_profile_id', $job_profile->id)->get();

            return apiResponse($profile_documents,  'success', 201);
            
        } else {
            return apiResponse([], 'Job profile not deleted', 404); 
        }
    }

    //delete an existing profile_document 
    public function delete_document($id) {

        $document = JobProfileDocument::find($id);

        if ($document) {

            $document->delete();
            if($document->type !=='link'){
                $path = parse_url($document->link)['path'];
                $path = ltrim($path, "/");
                delete($path);
            }
            

            return 'success';
        } else {
            return 'Profile document not deleted'; 
        }
    }

    public function uploadJobProfileImages(Request $request){

        $data = $request->all();
        info($data);

        $user = Auth::user();
        if (empty($user)) {
            return 'User  not found or user not logged'; 
        }
        $job_profile = $user->jobProfile;
        if (empty($job_profile)) {
            return 'Job profile  not found';
        }
        $field = '';
        if(isset($data['cover'])){
            
            $field = 'cover';
        }else{
            $field = 'logo';


        }
        $file = $data[$field];
            $validator = Validator::make(['file' => $file], [
                'file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);
        if($validator->fails()){
            return apiResponse(error_processor($validator), $validator->errors()->first(),420);
        }

            
        $link = $this->JobService->uploadFile($file);
        if($link){
            $data = [
                $field => $link
            ];

            if($job_profile->update($data)){
                return apiResponse([],'success');
            }else{
                info('failed to update');
                return apiResponse([],'an unexpected Error occurred',400);
            }
        }else{
            return apiResponse([],'an unexpected Error occurred',400);
        }
            



        
    }


}