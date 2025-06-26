<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        $user = User::first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found',
                'data' => null,
                'errors' => ['user' => 'No users in database']
            ], 404);
        }

        $addresses = Address::where('user_id', $user->id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Addresses retrieved successfully',
            'data' => $addresses->map(function ($address) {
                return [
                    'id' => $address->id,
                    'type' => $address->type,
                    'phone_number' => $address->phone_number,
                    'street_name' => $address->street_name,
                    'street_number' => $address->street_number,
                ];
            }),
            'errors' => null
        ], 200);
    }

    public function store(Request $request)
    {
        $user = User::first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found',
                'data' => null,
                'errors' => ['user' => 'No users in database']
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:home,office',
            'phone_number' => 'required|string|max:20',
            'street_name' => 'required|string|max:255',
            'street_number' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $validator->errors()
            ], 422);
        }

        $address = Address::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'phone_number' => $request->phone_number,
            'street_name' => $request->street_name,
            'street_number' => $request->street_number,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address created successfully',
            'data' => [
                'id' => $address->id,
                'type' => $address->type,
                'phone_number' => $address->phone_number,
                'street_name' => $address->street_name,
                'street_number' => $address->street_number,
            ],
            'errors' => null
        ], 201);
    }

    public function show($id)
    {
        $user = User::first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found',
                'data' => null,
                'errors' => ['user' => 'No users in database']
            ], 404);
        }

        $address = Address::where('id', $id)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found or not owned by user',
                'data' => null,
                'errors' => ['address' => 'Invalid address ID']
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Address retrieved successfully',
            'data' => [
                'id' => $address->id,
                'type' => $address->type,
                'phone_number' => $address->phone_number,
                'street_name' => $address->street_name,
                'street_number' => $address->street_number,
            ],
            'errors' => null
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found',
                'data' => null,
                'errors' => ['user' => 'No users in database']
            ], 404);
        }

        $address = Address::where('id', $id)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found or not owned by user',
                'data' => null,
                'errors' => ['address' => 'Invalid address ID']
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:home,office',
            'phone_number' => 'required|string|max:20',
            'street_name' => 'required|string|max:255',
            'street_number' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'data' => null,
                'errors' => $validator->errors()
            ], 422);
        }

        $address->update([
            'type' => $request->type,
            'phone_number' => $request->phone_number,
            'street_name' => $request->street_name,
            'street_number' => $request->street_number,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address updated successfully',
            'data' => [
                'id' => $address->id,
                'type' => $address->type,
                'phone_number' => $address->phone_number,
                'street_name' => $address->street_name,
                'street_number' => $address->street_number,
            ],
            'errors' => null
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found',
                'data' => null,
                'errors' => ['user' => 'No users in database']
            ], 404);
        }

        $address = Address::where('id', $id)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found or not owned by user',
                'data' => null,
                'errors' => ['address' => 'Invalid address ID']
            ], 404);
        }

        $address->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Address deleted successfully',
            'data' => null,
            'errors' => null
        ], 200);
    }
}