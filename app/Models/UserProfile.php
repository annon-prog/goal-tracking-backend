<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserProfile extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'otp',
        'otp_expiry',
    ];

    public function generateOtp()
    {
        $this->otp = rand(1000000, 999999);
        $this->otp_expiry = now()->addMinutes(1);
        $this->save();
    }

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
}