<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use App\Mail\VerifyEmail;

class AuthController extends Controller
{


    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }


    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;

              // Get job and parent profile images
            $profileImg = env("AWS_URL").'/'.optional(User::find($user->id)->profile)->image;
            $jobProfileImg = env("AWS_URL").'/'.optional(User::find($user->id)->jobProfile)->logo;

            // Add profile image and job profile image to the user object
            $user->profileImg = $profileImg;
            $user->jobProfileImg = $jobProfileImg;

           

            //check if user have verify his email if not send

            if (!$user->email_verified_at) {

                // Generate verification token
                $verificationToken = rand(1000, 9999);

                // Store the verification token
                DB::table('email_verifications')->insert([
                    'user_id' => $user->id,
                    'token' => $verificationToken,
                    'created_at' => now(),
                ]);

                // Send verification email
                try {
                    Mail::to($user->email)->send(new VerifyEmail($verificationToken));
                } catch (\Exception $exception) {

                    info($exception);
                }
            }

            return response(['user' => $user, 'access_token' => $token], 200);
        }

        $errors = [];
        array_push($errors, ['code' => 'auth-001', 'message' => 'Invalid Credentials']);
        return response()->json([
            'errors' => $errors
        ], 422);
    }



    public function registration(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'bail|required|string|min:3',
            'last_name' => 'bail|required|string|min:3',
            'email' => 'required|email:rfc,dns|max:255|unique:users',
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

        if (!$validate->fails()) {

            $data = request()->all();
            $response = $this->profileService->createUserProfile($data);
            return $response;
        } else {

            return response()->json(['errors' => error_processor($validate)], 422);
        }
    }



    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['data' => ''], 200);
    }



    public function getUser(Request $request)
    {


        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->first_name,
            'email' => $user->email,

        ]);
    }



    public function verify(Request $request)
    {
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
            $verificationRecord = DB::table('email_verifications')
                ->where('user_id', $user->id)
                ->where('token', $request->otp)
                ->first();
    
            if ($verificationRecord) {
                // Delete the verification record
                DB::table('email_verifications')
                    ->where('user_id', $verificationRecord->user_id)
                    ->delete();
                    
                    $user->email_verified_at = now();
                    $user->save();
    
                // Manually authenticate the user
                Auth::login($user);
    
                // Generate and send the token
                $token = $user->createToken('authToken')->accessToken;
    
                return response(['user' => $user, 'access_token' => $token], 200);
            } else {
                $errors = [['code' => 'auth-001', 'message' => 'Invalid token']];
                return response()->json(['errors' => $errors], 422);
            }
        } else {
            $errors = [['code' => 'auth-001', 'message' => 'Invalid email']];
            return response()->json(['errors' => $errors], 422);
        }
    }
    


    public function resend(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if ($user) {

            $verificationRecord = DB::table('email_verifications')
                ->where('user_id', $user->id)
                ->where('token', $request->token)
                ->first();

            if ($verificationRecord) {
                
                try {
                    $verificationToken = rand(1000, 9999);
                    Mail::to($user->email)->send(new VerifyEmail($verificationToken));

                    return response(['message' => 'Otp Resend'], 200);

                } catch (\Exception $exception) {
    
                    info($exception);
                }
               


            } 




        } 



            $verificationToken = rand(1000, 9999);
            DB::table('email_verifications')->insert([
                'user_id' => $user->id,
                'token' => $verificationToken,
                'created_at' => now(),
            ]);

            // Send verification email
            try {
                Mail::to($user->email)->send(new VerifyEmail($verificationToken));
                return response(['message' => 'Otp Resend'], 200);
            } catch (\Exception $exception) {

                info($exception);
            }
     
    }
}
