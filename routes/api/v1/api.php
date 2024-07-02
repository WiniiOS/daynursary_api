<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Profile\ChildProfileController;
use App\Http\Controllers\Api\V1\Profile\ProfileController;
use App\Http\Controllers\Api\V1\Center\CenterReviewController;
use App\Http\Controllers\Api\V1\Profile\CertificationController;
use App\Http\Controllers\Api\V1 as Api;

use App\Http\Controllers\Api\V1\Center\CenterController;


require_once __DIR__ . '/profileRoutes.php'; // Include the routes from workExperienceRoutes.php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'Api\V1'], function () {  



     //auth routes
     Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

        Route::post('register', 'AuthController@registration');
        Route::post('login', 'AuthController@login');
        Route::post('signin', 'AuthController@login');

        //verify and resend  

        Route::post('verify', 'AuthController@verify');
        Route::post('resend', 'AuthController@resend');

        //forgotpassword
        Route::post('sendEmail','ForgotPasswordController@sendResetLinkEmail');
        Route::post('validateToken','ForgotPasswordController@checkToken');
        Route::post('resetPassword','ForgotPasswordController@resetPassword');



        Route::middleware('auth:api')->group(function () {
            Route::get('user', 'AuthController@getUser');
            Route::get('logout', 'AuthController@logout');
            // Route::get('user', 'AuthController@getUser');

        });

    });



    Route::group(['prefix' => 'conversations', 'namespace' => 'Conversation'], function () { 

        Route::middleware('auth:api')->group(function () {   
      
         // Parent Profile Conversations
         Route::get('parent-profile', 'ConversationController@getParentProfileConversations');
    
         // Job Profile Conversations
         Route::get('job-profile', 'ConversationController@getJobProfileConversations');

         Route::post('create-parent-profile', 'ConversationController@createParentConversation');
         Route::post('create-job-profile', 'ConversationController@createJobConversation');
         Route::post('create-center-profile', 'ConversationController@createCenterParentProfileConversation');
         Route::post('create-job-job-profile', 'ConversationController@createJobJobProfileConversation');

         Route::post('create-parent-profile/message', 'ConversationController@createParentProfileCenterMessage');
         Route::post('create-job-profile/message', 'ConversationController@createJobProfileJobMessage');
         Route::post('create-center-profile/message', 'ConversationController@createCenterParentProfileMessage');
         Route::post('create-job-job-profile/message', 'ConversationController@createJobJobProfileMessage');

         Route::get('messages/{conversationId}', 'ConversationController@getConversationMessages');
        //  get a conversation messages   

        

         
   
        });

    });




    //config route to get the app configs like app name, logo etc

    Route::get('config', 'ConfigController@getConfig');
    Route::get('features','ConfigController@getAllFeatures');

    //country,city, and state apis
    Route::get('state/{id}', [ProfileController::class, 'getState']);
    Route::get('city/{id}', [ProfileController::class, 'getCity']);
    Route::get('country/{id}', [ProfileController::class, 'getCountry']);
    Route::get('country', [ProfileController::class, 'getCountries']);
    Route::get('country/{id}/states', [ProfileController::class, 'getCountryStates']);
    Route::get('state/{id}/cities', [ProfileController::class, 'getStateCities']);


    Route::post('upload', [ProfileController::class, 'upload']);


    Route::group(['prefix' => 'center', 'namespace' => 'Center'], function () {

        Route::get('popular', 'CenterController@getPopularCenters');
        Route::get('featured', 'CenterController@getFeaturedCenters');
        Route::get('all','CenterController@getAll');

        //full center details

        Route::get('details/{slug}', 'CenterController@centerDetails');
        Route::get('search', 'SearchController@SearchCenters'); 
        Route::get('search-summary', 'SearchController@SearchCentersSummary');
        //search suggestions here    

        Route::get('suggestions', 'SearchController@SearchSuggestions');
        Route::get('{slug}', 'CenterController@getCenter');


        Route::get('{slug}/applications', 'CenterController@applications');
       
        //center application   
        // Route::post('application/{slug}/apply', 'CenterController@chilCaredApplication')->middleware('auth:api');
        Route::middleware('auth:api')->group(function () {
            Route::get('get/favourite','CenterController@getFavCenters');
            //center application   
            Route::post('application/{slug}/apply', 'CenterController@chilCaredApplication');
            Route::post('update/favourite', 'CenterController@updateFavouriteCenter');
            
        });
    

       //reviews routes
       Route::group(['prefix' => 'reviews'], function () {
           
            Route::post('add_review', 'CenterController@add_review')->middleware('auth:api');
        // Route::get('review/{id}', 'CenterController@getSingle');
            Route::get('/{slug}', 'CenterController@get_reviews');
        });

        //Book a tour
        Route::group(['prefix' => 'book_a_tour'], function () {

            Route::post('add_book_a_tour', 'CenterController@add_book_a_tour');
            Route::get('get_book_a_tours', 'CenterController@get_book_a_tours');
            
        });
    });






Route::group(['prefix' => 'job', 'namespace' => 'Job'], function () {


        Route::get('featured', 'JobController@getFeaturedJobs');


        //full job details

        Route::get('details/{slug}', 'JobController@jobDetails');
        Route::get('search', 'JobSearchController@SearchJobs');

        //get all existing job features
        Route::get('all/featImmuCertSkill','JobController@getFeatCertSkillImmu');
        Route::get('all','JobController@getJobs');

        //search suggestions here 
        Route::middleware('auth:api')->group(function () {

            Route::post('apply/{id}', 'JobController@applyJob');
            Route::get('myjobs', 'JobController@getApplyJobs');
            Route::get('application-details/{id}', 'JobController@getApplyJobDetails'); 
            //add fav and viewed jobs
            Route::post('add/fav/job','JobController@addFavJob');
            Route::delete('remove/fav/job{id}','JobController@deleteFavJob');
            Route::post('add/viewed/job','JobController@addViewedJob');
            Route::delete('remove/viewed/job{id}','JobController@deleteViewedJob');
            Route::get('fav/jobs/','JobController@getUserFavJobs');
            Route::get('viewed/jobs/','JobController@getUserViewedJobs');

             //add job
             Route::post('add','JobController@addJob');
       

        });

        Route::get('{slug}', 'JobController@getJob');



    });




   


    Route::group(['prefix' => 'profile', 'namespace' => 'Profile'], function () {

        Route::middleware('auth:api')->group(function () {
  

            //profile application   
            Route::get('applications', 'ProfileController@applications'); 
            Route::get('application/{id}', 'ProfileController@application');


            //  Profile Parent 

            Route::post('profile', 'ProfileController@createProfile');
            Route::get('all', 'ProfileController@getProfileAll');
            Route::put('profile/{id}', 'ProfileController@editProfile');
            Route::get('profile/{id}', 'ProfileController@getProfile');
            Route::get('profile', 'ProfileController@getAuthProfile');

            Route::delete('profile/{id}', 'ProfileController@deleteProfile');
            Route::post('upload/{id}', 'ProfileController@uploadProfile');

            //  Profile Child
            Route::post('create', 'ChildProfileController@createdProfile');
            Route::get('getprofile/{id}', 'ChildProfileController@getProfile');
            Route::put('update/{id}', 'ChildProfileController@updateProfile');
            Route::delete('delete/{id}', 'ChildProfileController@deleteProfile');
            Route::get('getallChild/{id}', 'ChildProfileController@getChild');
            Route::get('getallChild', 'ChildProfileController@getAuthChildrenProfiles');
            Route::post('upload_Profile_image', 'ChildProfileController@uploadImage');

            Route::get('certifications_dropdown', 'CertificationController@getDropdownData');
            Route::get('get_certifications', 'CertificationController@getCertifications');
            Route::post('create_certification', 'CertificationController@createdCertifications');
            Route::put('update_certification/{id}', 'CertificationController@updateCertification');
            Route::delete('delete_certification/{id}', 'CertificationController@deleteCertification');

            Route::get('skills', 'ProfileSkillController@getSkills');
            Route::post('skills', 'ProfileSkillController@createSkill');
            Route::put('skills/{id}', 'ProfileSkillController@updateSkill');
            Route::delete('skills/{id}', 'ProfileSkillController@deleteSkill');
            //skill
            Route::post('/jobprofile/skill/save','ProfileSkillController@saveSkills');
            //save_profile_job_status
            Route::post('/jobprofile/job/status','ProfileSkillController@update_job_status');
        });
        //skill type
        Route::get('/jobprofile/skilltypes', 'ProfileSkillController@get_skill_types');
        Route::post('/jobprofile/skilltypes', 'ProfileSkillController@create_skill_type');
        Route::put('/jobprofile/skilltypes/{id}', 'ProfileSkillController@update_skill_type');
        Route::delete('/jobprofile/skilltypes/{id}', 'ProfileSkillController@delete_skill_type');
        
        Route::get('/jobprofile/skill', 'ProfileSkillController@get_skills');
        Route::post('/jobprofile/skill', 'ProfileSkillController@create_skill');
        Route::put('/jobprofile/skill/{id}', 'ProfileSkillController@get_skills');
        Route::delete('/jobprofile/skill/{id}', 'ProfileSkillController@delete_skill');

    });

    Route::group(['prefix' => 'conversations', 'namespace' => 'Conversation'], function () { 

        Route::middleware('auth:api')->group(function () {   
      
         // Parent Profile Conversations
         Route::get('parent-profile', 'ConversationController@getParentProfileConversations');
    
         // Job Profile Conversations
         Route::get('job-profile', 'ConversationController@getJobProfileConversations');

         Route::post('create-parent-profile', 'ConversationController@createParentConversation');
         Route::post('create-job-profile', 'ConversationController@createJobConversation');
         Route::post('create-center-profile', 'ConversationController@createCenterParentProfileConversation');
         Route::post('create-job-job-profile', 'ConversationController@createJobJobProfileConversation');

         Route::post('create-parent-profile/message', 'ConversationController@createParentProfileCenterMessage');
         Route::post('create-job-profile/message', 'ConversationController@createJobProfileJobMessage');
         Route::post('create-center-profile/message', 'ConversationController@createCenterParentProfileMessage');
         Route::post('create-job-job-profile/message', 'ConversationController@createJobJobProfileMessage');

         Route::get('messages/{conversationId}', 'ConversationController@getConversationMessages');
        //  get a conversation messages   

        

         
   
        });

    });

    //job
    Route::group(['prefix' => 'job', 'namespace' => 'Job'], function () {


        Route::get('featured', 'JobController@getFeaturedJobs');


        //full job details

        Route::get('details/{slug}', 'JobController@jobDetails');
        Route::get('search', 'JobSearchController@SearchJobs');

        //get all existing job features
        Route::get('all/featImmuCertSkill','JobController@getFeatCertSkillImmu');
        Route::get('all','JobController@getJobs');

        //search suggestions here 
        Route::middleware('auth:api')->group(function () {

            Route::post('apply/{id}', 'JobController@applyJob');
            Route::get('myjobs', 'JobController@getApplyJobs');
            Route::get('application-details/{id}', 'JobController@getApplyJobDetails'); 
            //add fav and viewed jobs
            Route::post('add/fav/job','JobController@addFavJob');
            Route::delete('remove/fav/job{id}','JobController@deleteFavJob');
            Route::post('add/viewed/job','JobController@addViewedJob');
            Route::delete('remove/viewed/job{id}','JobController@deleteViewedJob');
            Route::get('fav/jobs/','JobController@getUserFavJobs');
            Route::get('viewed/jobs/','JobController@getUserViewedJobs');

             //add job
             Route::post('add','JobController@addJob');
       

        });

        Route::get('{slug}', 'JobController@getJob');



    });
    //immunisations and work experience
    Route::middleware('auth:api')->group(function () {
        Route::get('/job/profile', 'WorkExperienceController@get_job_profile');
        //immunisation
        Route::post('/job/profile/immunisation', 'ImmunisationController@add_immu');
        Route::get('/job/profile/immunisation', 'ImmunisationController@immu_list');
        Route::put('/job/profile/immunisation/{id}', 'ImmunisationController@update_immu');
        Route::delete('/job/profile/immunisation/{id}', 'ImmunisationController@delete_immu');

        Route::get('/job/profile', 'WorkExperienceController@get_job_profile');


    });

});







    



    


   
    
   


    
