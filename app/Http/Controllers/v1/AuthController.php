<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\LoginRequest;
use App\Http\Requests\v1\Auth\RegisterRequest;
use App\Models\User;
use App\Models\v1\Role;
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
            $permissions = $user->role->permissions ?? ['*'];
            $token = $user->createToken('authToken',$permissions)->plainTextToken;
            return response()->json(['user'=>$user->load('role') ,'token' => $token], 200);
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
        $data['role_id'] = Role::where('name','user')->first()->id;
        $user = User::create($data);
        $userData = $user->load('role');
        $token = $user->createToken('authToken',json_decode($userData->role->permissions,true))->plainTextToken;
        return response()->json(['user'=> $userData,'token' => $token], 201);
    }
}
