<?php

namespace App\Http\Middleware;

use App\Models\CenterAdmin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\Vendor;
use App\Models\VendorEmployee;

class VendorTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token=$request->bearerToken();
      

        if(strlen($token)<1)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'auth-001', 'message' => 'Unauthorized.']
                ]
            ], 401);
        }
       
      
            $vendor = CenterAdmin::where('auth_token', $token)->first();
            


            if(!isset($vendor))
            {
                return response()->json([
                    'errors' => [
                        ['code' => 'auth-001', 'message' => 'Unauthorized.']
                    ]
                ], 401);
            }
            $request['vendor']=$vendor;

        return $next($request);
    }
}
