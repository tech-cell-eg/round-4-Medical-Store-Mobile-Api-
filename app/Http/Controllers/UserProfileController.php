<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\UserProfile;
use App\Http\Resources\UserProfileResource;
use App\Http\Requests\UserProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show(): JsonResponse
    {
        try {
            $user = Auth::user();

            $profile = UserProfile::firstOrCreate(
                ['user_id' => $user->id],
                ['is_active' => true]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Profile data retrieved successfully',
                'data' => new UserProfileResource($profile->load('user'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving data',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(UserProfileUpdateRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validated();


            $profile = UserProfile::firstOrCreate(
                ['user_id' => $user->id],
                ['is_active' => true]
            );
            Log::info($validatedData);


            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old image if it exists
                if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
                    Storage::disk('public')->delete($profile->profile_image);
                }


                // Upload new image
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
                $validatedData['profile_image'] = $imagePath;
            }

            // Update data
            $profile->update($validatedData);

            $user->update([
                "name" => $profile->getFullNameAttribute(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'data' => new UserProfileResource($profile->load('user'))
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteProfileImage(): JsonResponse
    {
        try {
            $user = Auth::user();
            $profile = UserProfile::where('user_id', $user->id)->first();

            if (!$profile) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Profile not found'
                ], 404);
            }

            // Delete image from storage
            if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
                Storage::disk('public')->delete($profile->profile_image);
            }


            // Remove image path from database
            $profile->update(['profile_image' => null]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile image deleted successfully',
                'data' => new UserProfileResource($profile->load('user'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
