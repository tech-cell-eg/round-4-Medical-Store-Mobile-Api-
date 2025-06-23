<?php

namespace App\Http\Controllers;

use App\Events\CreatedUser;
use App\Http\Requests\sendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\User;
use App\Models\Otp;
use App\Models\UserProfile;
use App\Services\TwilioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class UserAuthController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function sendOtp(sendOtpRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $phoneNumber = $request->input('phone');

            $this->cleanupOldOtps($phoneNumber);

            $otpCode = TwilioService::generateOtp();
            $expiresAt = now()->addMinutes(5);

            $otp = Otp::create([
                'phone' => $phoneNumber,
                'code' => $otpCode,
                'expires_at' => $expiresAt,
                'is_used' => false,
                'user_id' => $this->getUserIdByPhone($phoneNumber)
            ]);

            $smsResult = $this->twilioService->sendOtp($phoneNumber, $otpCode);

            if (!$smsResult['success']) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to send OTP. Please try again.',
                    'error' => $smsResult['error'] ?? 'Unknown error'
                ], 500);
            }

            DB::commit();

            $isNewUser = $this->isNewUser($phoneNumber);

            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully.',
                'data' => [
                    'phone' => $phoneNumber,
                    'expires_in_minutes' => 5,
                    'is_new_user' => $isNewUser
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $phoneNumber = $request->input('phone');
            $otpCode = $request->input('code');

            $otp = Otp::getValidOtp($phoneNumber, $otpCode);
            if (!$otp) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired OTP.',
                ], 400);
            }

            $otp->update(['is_used' => true]);

            $user = $this->firstOrCreateUser($phoneNumber);

            event(new CreatedUser($user)); // This event will Create User Profile

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Authentication successful.',
                'data' => [
                    'user_id' => $user->id,
                    'phone' => $user->phone,
                    'token' => $token,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    private function isNewUser(string $phoneNumber): bool
    {
        return ! User::where("phone", $phoneNumber)->exists();
    }

    private function getUserIdByPhone(string $phoneNumber): ?int
    {
        $user = User::where('phone', $phoneNumber)->first();
        return $user ? $user->id : null;
    }

    private function firstOrCreateUser(string $phoneNumber): User
    {
        $user = User::where("phone", $phoneNumber)->first();
        if ($user) {
            return $user;
        }

        return User::Create(
            [
                'phone' => $phoneNumber,
                'is_verified' => true
            ]
        );
    }


    private function cleanupOldOtps(string $phoneNumber): void
    {
        Otp::where('phone', $phoneNumber)
            ->where(function ($query) {
                $query->where('is_used', true)
                    ->orWhere('expires_at', '<', now());
            })
            ->delete();
    }
}
