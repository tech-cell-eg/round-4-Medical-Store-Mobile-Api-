<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Otp extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'code',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function isValid()
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }



    public static function getValidOtp(string $phoneNumber, string $otpCode)
    {
        return self::where('phone', $phoneNumber)
            ->where('code', $otpCode)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();
    }
}
