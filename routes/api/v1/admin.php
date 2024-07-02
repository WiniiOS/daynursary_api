<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;


Route::group(['namespace' => 'Api\V1\Admin'], function () {  

Route::post('claim/{center_id}', 'AuthController@claimCenter');
Route::post('register/{center_id}', 'AuthController@register');
Route::post('register-login/{center_id}', 'AuthController@registerLogin');
Route::post('login', 'AuthController@login');  
Route::post('center_admin', 'AuthController@getCenterAdmin');  


Route::middleware(['vendor.api'])->group(function () {
    
    Route::group(['prefix' => 'center'], function () {

        Route::post('create', 'CenterController@createCenter');
        Route::get('all', 'CenterController@getAllCenters'); 
        Route::get('details/{slug}', 'CenterController@getCenterDetails');

        //center service routes
        Route::post('details/add/center_service', 'CenterController@add_serviceCenter');
        Route::put('details/update/center_service/{id}', 'CenterController@update_serviceCenter');
        Route::get('details/get/center_service', 'CenterController@get_serviceCenter');
        Route::get('details/get/center_service/{id}', 'CenterController@get_serviceCenter');
        Route::delete('details/delete/center_service/{id}', 'CenterController@delete_serviceCenter');
    });

    //jobs routes here

    Route::group(['prefix' => 'job'], function () {  
        Route::get('all', 'JobController@getAdminJobs'); 
        Route::get('details/{slug}', 'JobController@getJobDetails');
        Route::post('image/update','JobController@updateJobImage');

        //feature job route
        Route::post('add/feature', 'JobController@add_featureJob');
    });  


    Route::group(['prefix' => 'conversations'], function () {  
       Route::get('all', 'ConversationController@getCenterAdminConversations');
    }); 

    
    
    
});

});