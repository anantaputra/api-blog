<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request) : JsonResponse
    {

        $user = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(auth()->attempt($user)) {

            $token = auth()->user()->createToken('token')->accessToken;

            return response()->json([
                'code' => 200,
                'status' => 'ok',
                'data' => [
                    [
                        'id' => auth()->user()->id,
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'token' => $token,
                    ],
                ],
            ], 200);

        }

        return response()->json([
            'code' => 401,
            'status' => 'unauthorized',
            'errors' => [
                'message' => 'Unauthorized'
            ]
        ], 401);
        
    }
}
