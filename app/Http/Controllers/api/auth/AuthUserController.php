<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthUserController extends Controller
{
    public function login(Request $request)
    {
        $data =  $request->validate([
            "email" => "required",
            "password" => "required",
        ]);

        if( Auth::attempt($data)){
            $user = Auth::user();

            $token = $user->createToken($request->userAgent() , ['user']);

            return response()->json([
                "token" => $token->plainTextToken,
                "user" => $user
            ]);
        }

    }


    public function register(AuthUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            "name"=> $data["name"],

            "email"=>$data["email"],
            "password"=> Hash::make($data["password"])
        ]);

        if($user){
            return response()->json([
                'msg'=> 'register successfully',
                'user'=>$user,
                ]);
        }

        return response()->json(["msg" => "register failed"]);
    }


    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        $personal_access_token = PersonalAccessToken::findToken($token);

        $token_type = $personal_access_token->tokenable_type;

        if( ! $token_type === "App\Models\User"){
            return response()->json(['msg' => 'Access Denied']);
        }
        // Revoke access token
        if($personal_access_token){
            $personal_access_token->delete();
            return response()->json(['msg' => 'Logout Successful']);
        }

        return  response()->json(['msg' => 'Logout failed']);
    }
}
