<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request) : JsonResponse
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:6'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {

            return response()->json([
                'code' => 400,
                'status' => 'bad request',
                'errors' => $validator->errors()
            ], 400);

        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
 
        $token = $user->createToken('token')->accessToken;

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'data' => [
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'token' => $token,
                ],
            ],
        ], 200);
 
    }
}
