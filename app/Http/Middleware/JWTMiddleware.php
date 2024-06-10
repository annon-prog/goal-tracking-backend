<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{

            Log::info('Request headers: ', ['headers' => $request->headers->all()]);

            $token = JWTAuth::getToken();
            Log::info('Token: ', ['token' => (string)$token]);

            $payload = JWTAuth::getPayload($token);
            Log::info('Payload: ', ['payload' => $payload->toArray()]);

            $user = auth()->user();
            Log::info('User: ', ['user' => $user]);


            if(!$user){
                return response()->json(['message' => 'user not found'], 500);
            }
        }catch(JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return $next($request);
    }
}
