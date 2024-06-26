<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Middleware\JWTMiddleware;

Route::group([],function(){
    Route::post('/signup', [UserProfileController::class, 'signUp']) ->name('signup');
    Route::post('/login', [UserProfileController::class, 'login'])->name('login');
    });

Route::group([],function(){
    Route::get('/dashboard', function(){
        return response()->json(['message' =>'Welcome to the dashboard'], 200);
    });
})->middleware('token');