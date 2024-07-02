<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; 
use App\Mail\VerifyEmail;


class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request){
        $data = $request->all();
        $validated = Validator::make($data, [
            'email' => 'required|email',
        ]);
        if($validated->fails()){
            return apiResponse(error_processor($validated),$validated->errors()->first(), 422);
        }

        $User = User::where('email',$data['email'])->first();
        if(!$User){
            return apiResponse([],'User not found..', 404);
        }
        $token = $this->createPasswordResetToken($data['email']);
        $this->sendResetLinkEmailToUser($data['email'],$token);

        return apiResponse([],'success',200);

    }

    protected function createPasswordResetToken($email){
       try{
                $token = mt_rand(100000, 999999); 
                $existing_token = DB::table('password_reset_tokens')
                ->where('email', $email);
                if($existing_token){
                    $existing_token->delete();
                }
                DB::table('password_reset_tokens')->insert([
                    'email'=>$email,
                    'token'=>$token,
                    'created_at'=>Carbon::now()
                ]);

                return $token;
       }
       catch(\Exception $exception){
        info($exception);
       }

    }

    protected function sendResetLinkEmailToUser($email,$token){
        try{

            $data = [
                'token' => $token,
            ];
            Mail::to($email)->send(new VerifyEmail($token));
            // Mail::send('emails.password-reset', $data, function ($message) use ($email) {
            //     $message->to($email)->subject('Reset Your Password');
            // });


            // $resetLink = config('app.url').'/reset-password?token='.$token;
            //     Mail::send('emails.password-reset',['resetLink'=>$resetLink],function ($message) use ($email){
            //     $message->to($email)->subject('reset your password');
            //  });
        }
        catch(\Exception $exception){
            info($exception);
        }
    }

    public function checkToken(Request $request){
        try{
            $request->validate([
                'email' => 'required|email',
                'token' => 'required',
            ]);
    
            $email = $request->input('email');
            $token = $request->input('token');
    
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('token', $token)
                ->first();
    
            if (!$resetRecord) {
                return apiResponse([],'Invalid token. Please try again.', 400);
            }
            // Check if the token is expired (e.g., valid for 24 hours)
            $resetCreatedAt = Carbon::parse($resetRecord->created_at);
            if ($resetCreatedAt->addMinutes(10)->isPast()) {
                return  apiResponse([],'The password reset token has expired. Please request a new one.', 400);
            }
    
            return apiResponse([],'success', 200);
        }
        catch(\Exception $exception){
            info($exception);
            return apiResponse([],'an unexpected error occured', 500);
        }
    }

    public function resetPassword(Request $request){
        try{
            $request->validate([
                'email' => 'required|email',
                'password' => [
                    'required', 'string', 'min:8',
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[%!&*$#@|+]/',
                    'regex:/[a-z]/'
                ],
            ]);
    
            $email = $request->input('email');
            $password = $request->input('password');
    
            $user = User::where('email', $email)->first();
            if (!$user) {
                return apiResponse([],'We could not find a user with that email address.', 404);
            }
    
            $user->password = Hash::make($password);
            $user->save();
    
            // Delete the used password reset record
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();
    
            return apiResponse([],'success',200);
        }
        catch(\Exception $exception){
            info($exception);
            return apiResponse([],'an unexpected error occured',500);

        }

    }
}

