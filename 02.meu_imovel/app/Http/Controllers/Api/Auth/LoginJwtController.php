<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Api\Messages\ApiMessages;

class LoginJwtController extends Controller
{
    public function login(Request $request) 
    {
        $credentials = $request->all(['email', 'password']);
        
        $token = auth('api')->attempt($credentials);
       
        if (!$token) {
            $message = new ApiMessages('Unauthorized');

            return response()->json($message->getMessage(), 401);
        }

        return response()->json(['token' => $token], 200);
    }
}
