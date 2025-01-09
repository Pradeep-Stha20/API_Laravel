<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request){
        $validatorUser = Validator::make(  // we created variable which checks for validation
            $request->all(),
            [
                'name'=> 'required',
                'email'=> 'required |email |unique:users,email',
                'password'=> 'required',
            ]
            );
            if($validatorUser->fails()){
                return response()->json([
                    'status'=> false,
                    'message' => 'Validation Error',
                    'errors' => $validatorUser->errors()->all()
                ],401);
            }
            $user = User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> $request->password,
                ]);
            return response()->json([
                    'status'=> true,
                    'message' => 'User Created Successfully',
                    'user'=> $user,
                ],200);
            
    }
    
    public function login(Request $request){
        $validatorUser = Validator::make(  // we created variable which checks for validation
            $request->all(),
            [
                'email'=> 'required |email',
                'password'=> 'required',
            ]
            );
            if($validatorUser->fails()){
                return response()->json([
                    'status'=> false,
                    'message' => 'Authentication Fail',
                    'errors' => $validatorUser->errors()->all()
                ],404);
            }
            // here attempt() method check if username and password are matching in database
            if(Auth::attempt(['email'=> $request->email,'password'=> $request->password])){
                $authUser = Auth::user();
                return response()->json([
                    'status'=> true,
                    'message' => 'User Logged in Successfully',
                    'token' => $authUser->createToken('API Token')->plainTextToken,  //createToken is a method of Sanctum
                    'token_type' => 'bearer'
                ],200);
            }
            else{
                return response()->json([
                    'status'=> false,
                    'message' => 'Email & Password not matched',
                ],404);
            }
    }
    public function logout(Request $request){
        $user = $request->user();
        $user->tokens()->delete();

        
                return response()->json([
                    'status'=> true,
                    'user'=> $user,
                    'message' => 'User Logged Out Successfully',
                ],200);
    }

}
