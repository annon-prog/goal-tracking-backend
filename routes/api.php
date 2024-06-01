<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
});


Route::group([],function(){
    Route::post('/signup', [UserProfileController::class, 'signUp']) ->name('signup');
    Route::post('/login', [UserProfileController::class, 'login'])->name('login');
    });