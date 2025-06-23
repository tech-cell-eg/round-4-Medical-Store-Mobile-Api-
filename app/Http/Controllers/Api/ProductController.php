<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * عرض قائمة المنتجات
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['category', 'brand', 'unit'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    /**
     * تخزين منتج جديد في قاعدة البيانات
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode',
            'is_active' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // إنشاء المنتج
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->barcode = $request->barcode ?? Str::random(13);
        $product->is_active = $request->is_active ?? true;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->created_by = 1;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم إنشاء المنتج بنجاح',
            'data' => $product->load(['category', 'brand', 'unit'])
        ], 201);
    }

    /**
     * عرض تفاصيل منتج محدد
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'brand', 'unit'])->find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    /**
     * تحديث المنتج المحدد في قاعدة البيانات
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode,' . $id,
            'is_active' => 'boolean',
            'category_id' => 'sometimes|required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'sometimes|required|exists:units,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // تحديث المنتج
        $product->fill($request->only([
            'name',
            'description',
            'price',
            'quantity',
            'barcode',
            'is_active',
            'category_id',
            'brand_id',
            'unit_id'
        ]));
        $product->updated_by = 1;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث المنتج بنجاح',
            'data' => $product->load(['category', 'brand', 'unit'])
        ]);
    }

    /**
     * حذف المنتج المحدد من قاعدة البيانات
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف المنتج بنجاح'
        ]);
    }
}
