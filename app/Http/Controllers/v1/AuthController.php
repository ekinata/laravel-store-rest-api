<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\LoginRequest;
use App\Http\Requests\v1\Auth\RegisterRequest;
use App\Models\User;
class AuthController extends Controller
{
    /**
     * Login Controller
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (auth()->attempt($credentials)){
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['user'=>$user ,'token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Current Token Logout Controller
     */
    public function logout(){
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }

    /**
    * Global Logout Controller
    */
    public function globalLogout(){
    auth()->user()->tokens()->delete();
    return response()->json(['message' => 'Logged out from all devices'], 200);
    }

    /**
     * Register Controller
     */
    public function register(RegisterRequest $request){
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(['user'=>$user ,'token' => $token], 201);
    }
}
