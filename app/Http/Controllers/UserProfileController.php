<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserProfileController extends Controller
{
    public function signUp(Request $request, Response $response){

        try{
        //validate the requests
        $request->validate([
            'username' =>'required|max:255',
            'email' =>'required|email|max:255|unique:user_profiles,email',
            'password'=>'required|min:8|confirmed'
        ]);

        //Assign the request inputs to variables
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');

        //Create an instance of userProfile model
        $userProfile = new UserProfile([
            'username' => $username,
            'email' => $email,
            'password'=> Hash::make($password)
        ]);

        //save the userProfile instance
        $userProfile->save();

         //Varaible used to get the response status code which is used in the success response body
            $status = $response->getStatusCode();

            //generate JWTAuth tokens
            $token = JWTAuth::fromUser($userProfile);

        //return a response in json format
        return response()->json([
                "status" => $status,
                "message" => "user was successfully created",
                "token" => $token
        ], 201);

        //catch any errors
        } catch (\Exception $e) {
            Log::error('Error in signUp: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred'], 500);
        }

    }

    public function login(Request $request, Response $response){
        try{
        
        //Validate the requests
        $request->validate([
            'login' => 'required|string',
            'password'=>'required|string|min:8',
        ]);

        //Assign the request inputs to variables
        $login = $request->input('login');
        $password =$request->input('password');

        //Db query to select user where the email or username is the request received
        $user = UserProfile::where('email', $login)
                    ->orWhere('username', $login)
                    ->first();

        //Varaible used to get the response status code which is used in the success response body
        $status = $response->getStatusCode();

       


        //Condition to check if the query and the password obtained are correct
        if($user && Hash::check($password, $user->password)){

            //generate JWTAuth tokens
            $token = JWTAuth::fromUser($user);

            //return the response with the token generated.
            return response()->json([
                "status" => $status,
                "message" => "user was successfully logged in",
                "token" => $token
            ], 200);
        }

        //catch  any errors
    } catch(\Exception $e){
        Log::error("error during login", $e->getMessage());
        return response()->json(['error' => 'An error occured'], 500);
    }
    }
}
