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


<<<<<<< HEAD
    /**
     * الحصول على تحديثات المخزون التي قام بها هذا المستخدم
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockUpdates()
    {
        return $this->hasMany(Stock::class, 'last_updated_by');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
=======
    public function otps()
>>>>>>> b2c02a82f4161f389c2d46ca2c0a9ad205bfb5fa
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
