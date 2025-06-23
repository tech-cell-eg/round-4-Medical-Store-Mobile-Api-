<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="المنتجات",
 *     description="إدارة المنتجات"
 * )
 */

class ProductController extends Controller
{
    /**
     * عرض قائمة المنتجات مع إمكانية التصفية والبحث
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        $query = Product::with(['category', 'brand', 'unit']);

        // البحث بالاسم أو الوصف
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // التصفية حسب الفئة
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // التصفية حسب الماركة
        if ($brandId = $request->input('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        // التصفية حسب الحالة
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // الترتيب
        $query->latest();

        $products = $query->paginate($perPage);

        return new ProductCollection($products);
    }

    /**
     * عرض تفاصيل منتج محدد
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::with([
            'category',
            'brand',
            'unit',
            'packages',
            'ingredients',
            'reviews'
        ])->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        return response()->json([
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * إنشاء منتج جديد
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        // معالجة رفع الصورة
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        // إنشاء المنتج
        $product = Product::create($data);

        return response()->json([
            'message' => 'تم إنشاء المنتج بنجاح',
            'data' => new ProductResource($product->load(['category', 'brand', 'unit']))
        ], 201);
    }

    /**
     * تحديث منتج محدد
     */
    public function update(ProductRequest $request, string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        $data = $request->validated();

        // معالجة تحديث الصورة إذا تم رفع صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return response()->json([
            'message' => 'تم تحديث المنتج بنجاح',
            'data' => new ProductResource($product->load(['category', 'brand', 'unit']))
        ]);
    }

    /**
     * حذف منتج محدد
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'المنتج غير موجود'
            ], 404);
        }

        // حذف الصورة إذا كانت موجودة
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'تم حذف المنتج بنجاح'
        ]);
    }
}
