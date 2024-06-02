<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;



Route::group([],function(){
    Route::post('/signup', [UserProfileController::class, 'signUp']) ->name('signup');
    Route::post('/login', [UserProfileController::class, 'login'])->name('login');
    });