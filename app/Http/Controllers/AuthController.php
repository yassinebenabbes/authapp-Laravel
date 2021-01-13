<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            
            if (Auth::attempt($request->only('email', 'password'))) { // attempt return true if authentication is success, Otherwise false
               
                $user = Auth::user(); //get authenticated user
                $token = $user->createToken($user['first_name'])->accessToken; //create token
                return response([
                    'message' => 'success',
                    'token' => $token,
                    'user' => $user
                ]); // return response with user authentiocated and token generated

            }

        } catch (Exception $E) {
            return response([
                'message' => $E->getmessage()
            ], 400); // return message exception error 
        }

        return response([
            'message' => 'Invalid username/password'
        ], 401); //return invalid user or pass if attempt is false
    
    }
    
    public function getuser(){
    
        return Auth::user(); //Retrieve the currently authenticated user by using middeleware auth:api in api.php
        
    }


    public function register(Request $request){
        try{
            
            $validateddata = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'password_confirm' => 'required|same:password'
            ]); // validate data before creating user  
            
            $user = User::create([
                'first_name' => $validateddata['first_name'],
                'last_name' => $validateddata['last_name'],
                'email' => $validateddata['email'],
                'password' => Hash::make($validateddata['password'])
            ]);//creating user validating data 

            return $user; //return the information of the new user

        }catch(Exception $Ex){
            return response([
                'message' =>  $Ex->getmessage()
            ], 400); // return message exception error
        }
        
    }
}
