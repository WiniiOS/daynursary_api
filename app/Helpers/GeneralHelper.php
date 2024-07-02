<?php

use App\Models\GHLAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\RefreshTokenException;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Http;

  

     function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }

   

 function apiResponse($data, $message = '', $statusCode = 200, $meta = '', $requestValues = '')
{
    $message = (is_array($message)) ? reset($message) : $message;

    $response['data'] = ($data) ?? [];
    $response['requestValues'] = ($requestValues) ?? [];
    $response['message'] = (is_array($message)) ? $message[0] : $message;

    if (!empty($meta)) {
        $response = array_merge($response, $meta);
    }

    return response()->json($response, $statusCode);
} 


 function storeFile($file)
{  
    
    $filePath = 'tasks/daynursary/profiles'.'/' . str_replace(' ', '-', $file->getClientOriginalName());
  
    Storage::disk('s3')->put($filePath, file_get_contents($file));   
      return  $filePath; 

    // THIS IS TO GET THE FULL PATH  dd(env("AWS_URL").'/'.$filePath);  
}

function delete($path)
{
    try {
        if (Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->deleteDirectory($path);
        }
    } catch (\Throwable $th) {
        return '';
    }
    return '';
} 


function GhlAccessToken() {
    return GHLAccessToken::latest()->first();
}



 function handleResponse($res,  $fxn, $claim=null, $extra = null, $withCode = false, )
{ 
    $refreshed = false;

  if (!$res->successful()) {
    if($res->getStatusCode() == 401){
      if ($refreshed) {
        throw new RefreshTokenException();
      } else {
        $refreshed = true;
        return refreshToken($fxn, $extra, $claim);
      }
    }else{
      throw new CustomException(json_encode(json_decode($res->getBody())));
    }
  }

  if ($withCode) return [json_decode($res->getBody()), $res->getStatusCode()];
  return json_decode($res->getBody());
}  



 function refreshToken($fxn, $cliam=null, $extra = null, $withCode = false)
{  

   // $code = $request->code;
    $clientId = env('clientId');
    $clientSecret = env('clientSecred');
    $redirect_url = url('/callback');

  try { 

    $response = Http::asForm()->post('https://api.msgsndr.com/oauth/token', [
      'grant_type' => 'refresh_token',
      'client_id' => $clientId,
      'client_secret' => $clientSecret,
      'redirect_uri' => $redirect_url,
      'refresh_token' => GhlAccessToken()->refresh_token,
    ]);


    if (!$response->successful()) {
      throw new RefreshTokenException();
    }

     GHLAccessToken::updateOrCreate([], $response->json()); 

    return $extra ? $fxn($extra, $cliam) :  $fxn( $withCode);
  } catch (\Exception $e) {

    throw new RefreshTokenException(message: $e->getMessage());
    // return null;
  }
}



   

    

