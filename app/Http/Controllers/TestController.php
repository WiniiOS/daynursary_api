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

class TestController extends Controller
{
   

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();
        $token = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $token]);
    }

    return response(['error' => 'Invalid credentials'], 401);
}



    public function registration(Request $request)
    {
        $validate = Validator::make($request->all(), [ 
            'first_name' => 'bail|required|string|min:3',
            'last_name' => 'bail|required|string|min:3',
            'email' => 'required|email:rfc,dns|max:255|unique:users',
            'password' => ['required', 'string', 'min:8',
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[%!&*$#@|+]/',
            'regex:/[a-z]/',
            'confirmed'],
        ]);

        $validation_errors = $validate->errors();

        if(!$validate->fails()){
            $data = request()->all();  

            $user = User::create([
                'uuid' => Uuid::uuid4(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']), 
            ]);

            $user->assignRole("user");

            return Response()->json([
                'message' => "success"
            ], 201);
            
        }else{
            return Response()->json([
                'message' => $validation_errors->first(),
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response(['message' => 'Successfully logged out']);
    }

}
