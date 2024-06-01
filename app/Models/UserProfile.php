<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
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
}
