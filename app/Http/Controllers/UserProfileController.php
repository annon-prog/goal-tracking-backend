<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JWTServices;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    protected $jwtService;

    public function __construct(JWTServices $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function signUp(Request $request){

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

        Log::info('User registration: ', ['userProfile' => $userProfile]);

        //save the userProfile instance
        $userProfile->save();

        //generate JWTAuth tokens
    $token = $this->jwtService->generate(['email' => $email, 'password' => $password]);


        $tokenTTL = $this->jwtService->getTokenExpiry();

        //return a response in json format
        return response()->json([
                "message" => "user was successfully created",
                "expires_in" => $tokenTTL,
                "token" => $token
        ], 201);

        //catch any errors
        } catch (\Exception $e) {
            Log::error('Error in signUp: ', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Username or email already exists'], 500);
        }

    }

    public function login(Request $request){
        try{
        
        //Validate the requests
        $request->validate([
            'login' => 'required|string',
            'password'=>'required|string|min:8',
        ]);

        //Assign the request inputs to variables
        $login = $request->input('login');
        $password =$request->input('password');

        Log::info('Login Credentials', ['login' => $login, 'password' => $password]);

        //Db query to select user where the email or username is the request received
        $user = UserProfile::where('email', $login)
                    ->orWhere('username', $login)
                    ->first();

    
        Log::info('User data', ['user' => $user]);

        //Condition to check if the query and the password obtained are correct
        if($user && Hash::check($password, $user->password)){


            //generate JWTAuth tokens
        $token = $this->jwtService->generate(['email' => $user->email, 'password' => $password]);
        

            $tokenTTL = $this->jwtService->getTokenExpiry();

            //return the response with the token generated.
            return response()->json([
                "message" => "user was successfully logged in",
                "expires_in" =>$tokenTTL,
                "token" => $token
            ], 200);
        }

        //catch  any errors
    } catch(\Exception $e){
Log::error("error during login", ['error' => $e->getMessage()]);

        return response()->json(['error' => 'An error occured'], 500);
    }
    }
}
