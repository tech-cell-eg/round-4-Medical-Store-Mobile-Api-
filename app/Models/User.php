<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [
        'id',
    ];


    public function otps()
    {
        return $this->hasMany(Otp::class);
    }


    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }


    public function markPhoneAsVerified()
    {
        $this->update(['is_verified' => now()]);
    }
}
