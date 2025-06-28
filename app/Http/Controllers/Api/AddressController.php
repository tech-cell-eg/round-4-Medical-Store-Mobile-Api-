<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'addressLine1' => 'required|string',
            'addressLine2' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);
        $address = Address::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'address_line1' => $request->addressLine1,
            'address_line2' => $request->addressLine2,
            'is_default' => $request->is_default,
        ]);

        return response()->json($address);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'addressLine1' => 'required|string',
            'addressLine2' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        $address = Address::findOrFail($id);
        $address->update($request->validated());
        return response()->json($address);
    }

    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();
        return response()->json(null, 204);
    }

    public function setDefault($id)
    {
        $address = Address::findOrFail($id);
        $address->is_default = true;
        $address->save();
        return response()->json($address);
    }

}
