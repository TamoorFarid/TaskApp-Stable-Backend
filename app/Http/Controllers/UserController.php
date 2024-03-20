<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            "name" => "required",
            "email" => "required | email | unique:users",
            "password" => "required | confirmed",
        ]);
        if($validator->fails()){
            return response()->json(["error"=>$validator->errors()]);
        }
        $user = User::create([
            "name"=> $request->name,
            "email"=> $request->email,
            "password"=> Hash::make($request->password),
        ]);
        if($user){
            return response()->json([
                "success" => true,
                "message"=>"User registered successfully"
            ]);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            "email" => 'required',
            "password" => 'required',
        ]);
        if($validator->fails()){
            return response()->json(["error"=>$validator->errors()]);
        }else{
           $auth = Auth::attempt([
                "email" => $request->email,
                "password" => $request->password,
            ]);
            if($auth){
                $user = Auth::user();
                // here the user() returns a user object
                $token = $user->createToken('access-token')->accessToken;
                return response()->json([
                    "success" => true,
                    "message" => "User logged in successfully",
                    "user" => $user,
                    "token" => $token
                ]);
            }else{
                return response()->json([
                    "success" => false,
                    "message" => "Invalid login credentials"
                ]);
            }
        }
    }

    public function profile(){
        $user = Auth::user();

        if($user){
            return response()->json([
                "success" => true,
                "message" => "Profile information",
                "user" => $user
            ]);
        }
    }

    public function logout(){
        $user = Auth::user();
        if($user){
            $user->token()->revoke();
            return response()->json(
                [
                    "success" => false,
                    "message" => "User logged out successfully"
                ]
                );

        }
    }
}
