<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Api\Messages\ApiMessages;

class LoginJwtController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->all(['email', 'password']);

        Validator::make($credentials, [
            'email' => 'required|string',
            'password' => 'required|string'
        ])->validate();

        $token = auth('api')->attempt($credentials);
       
        if (!$token) {
            $message = new ApiMessages('Unauthorized');

            return response()->json($message->getMessage(), 401);
        }

        return response()->json(['token' => $token], 200);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logout efetuado com sucesso!'], 200);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();

        return response()->json(['token' => $token], 200);
    }
}
