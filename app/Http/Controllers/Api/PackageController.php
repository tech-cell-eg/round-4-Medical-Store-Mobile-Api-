<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::with('product')->get();
        return response()->json([
            'status' => 'success',
            'data' => $packages
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:255|unique:packages,sku',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'البيانات غير صالحة',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $package = Package::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم إنشاء العبوة بنجاح',
            'data' => $package
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $package = Package::with('product')->find($id);
        
        if (!$package) {
            return response()->json([
                'status' => 'error',
                'message' => 'العبوة غير موجودة'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $package
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $package = Package::find($id);
        
        if (!$package) {
            return response()->json([
                'status' => 'error',
                'message' => 'العبوة غير موجودة'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'sometimes|required|exists:products,id',
            'size' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'sku' => 'sometimes|nullable|string|max:255|unique:packages,sku,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'البيانات غير صالحة',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $package->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث العبوة بنجاح',
            'data' => $package
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $package = Package::find($id);
        
        if (!$package) {
            return response()->json([
                'status' => 'error',
                'message' => 'العبوة غير موجودة'
            ], Response::HTTP_NOT_FOUND);
        }

        $package->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف العبوة بنجاح'
        ], Response::HTTP_OK);
    }
}
