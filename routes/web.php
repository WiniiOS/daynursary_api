<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\SocialiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/{any}', function () {
//     return view('welcome');
// })->where('any', '.*');

Route::get('authentication-failed', function () {
    $errors = [];
    array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthenticated.']);
    return response()->json([
        'errors' => $errors,
    ], 401);
})->name('authentication-failed');


// La redirection et le callback vers le provider
Route::get("redirect/{provider}", [SocialiteController::class, "redirect"]);
Route::get("callback/{provider}", [SocialiteController::class, "callback"]);

Route::get("ghl/connect", [SocialiteController::class, "ghlConnect"])->name('ghl.connect'); 
Route::get("callback", [SocialiteController::class, "ghlCallback"])->name('ghl.callback');

//for testing purposes
Route::get("ghl/create/location", [SocialiteController::class, "ghlCreateLocation"])->name('ghl.create.location');
Route::get("ghl/create/user", [SocialiteController::class, "ghlCreateUser"])->name('ghl.create.user');