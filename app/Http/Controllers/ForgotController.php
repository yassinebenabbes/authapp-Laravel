<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ForgotController extends Controller
{
    //
    public function forgot(Request $request)
    {

        try {
            $validateddata = $request->validate([
                'email' => 'required|email',
            ]);//validate data email is required

            $email = $validateddata['email'];//get value of email 

            if (User::where('email', $email)->doesntExist()) {
                return response([
                    'message' => 'User inexistant'
                ], 403);
            } // user with this email it dosent exist

            $token = Str::random(10);//generate random token

            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token
            ]); // insert email and token into password_resets

            //sending mail with SwiftMailer library

            return response([
                'message' => 'check your email',
                'token' => $token
            ]); //sending message to check email with token 

        } catch (Exception $ex) {
            return response([
                'message' => $ex->getmessage()
            ], 400); // return message exception error 
        }
    }

    public function reset(request $request)
    {
        try {
            $validateddata = $request->validate([
                'token' => 'required',
                'password' => 'required',
                'password_confirm' => 'required|same:password'
            ]); //validate data token and pass required and pass confi should be the same

            $token = $validateddata['token'];
            if (!$pwd = DB::table('password_resets')->where('token', $token)->first()) {
                return response([
                    'message' => 'token invalide'
                ], 403); 
            } // check if this token exists in table password resets

            if (!$user = User::where('email', $pwd->email)->first()) {
                return response([
                    'message' => 'User inexistant'
                ], 403); 
            } // check if this user exists in table users

            $user->password = Hash::make($validateddata['password']); // hash pass to update
            $user->save(); // update user 

            return response([
                'message' => 'updated successfuly'
            ]); //password updated successfuly
            
        } catch (Exception $ex) {
            return response([
                'message' => $ex->getmessage()
            ], 400); // return message exception error 
        }
    }
}
