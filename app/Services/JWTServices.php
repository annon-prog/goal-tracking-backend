<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;

class JWTServices
{
    public function generate(array $credentials)
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return false;
            }
        } catch (JWTException $e) {
            return false;
        }

        return $token;
    }

    public function refresh($token){
        try{
            $refreshedToken=JWTAuth::refresh($token);
            return $refreshedToken;
        }catch(JWTException $e){
            return false;
        }
    }

    public function getTokenExpiry(){
        return JWTAuth::factory()->getTTL()*60;
    }
}