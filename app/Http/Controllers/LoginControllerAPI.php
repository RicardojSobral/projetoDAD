<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LoginControllerAPI extends Controller{
    public function login(Request $request)
    {
        $http = new Client();
        $response = $http->post(config('app.server_url') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('app.client_id'),
                'client_secret' => config('app.client_secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => ''
            ],
            'exceptions' => false,
        ]);
        $errorCode= $response->getStatusCode();
        if ($errorCode=='200') {
            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function logout()
    {
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Token revoked'], 200);
    }

}
