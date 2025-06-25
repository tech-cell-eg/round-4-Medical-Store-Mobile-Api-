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
use Illuminate\Pagination\LengthAwarePaginator;
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

    /**
     * بحث متقدم في المنتجات
     */
    public function advancedSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:1',
            'per_page' => 'integer|min:1|max:100',
            'category_id' => 'integer|exists:categories,id',
            'brand_id' => 'integer|exists:brands,id',
            'unit_id' => 'integer|exists:units,id',
            'is_active' => 'boolean',
            'exclude_expired' => 'boolean',
            'sort_by' => 'string|in:newest,oldest,name_asc,name_desc,active_first',
            'price_min' => 'numeric|min:0',
            'price_max' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات البحث غير صحيحة',
                'errors' => $validator->errors()
            ], 422);
        }

        $searchQuery = $request->input('q');
        $perPage = $request->input('per_page', 15);

        // بناء استعلام البحث العادي
        $query = Product::with(['category', 'brand', 'unit', 'packages']);

        // البحث في الاسم والوصف
        $query->where(function ($q) use ($searchQuery) {
            $q->where('name', 'like', "%{$searchQuery}%")
                ->orWhere('description', 'like', "%{$searchQuery}%");
        });

        // الفلاتر المتقدمة
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId = $request->input('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        if ($unitId = $request->input('unit_id')) {
            $query->where('unit_id', $unitId);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->boolean('exclude_expired', false)) {
            $query->where('expiry_date', '>=', now()->format('Y-m-d'));
        }

        // فلترة حسب السعر
        if ($priceMin = $request->input('price_min')) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax = $request->input('price_max')) {
            $query->where('price', '<=', $priceMax);
        }

        // تطبيق الترتيب
        if ($sortBy = $request->input('sort_by')) {
            switch ($sortBy) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'active_first':
                    $query->orderBy('is_active', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        // تنفيذ البحث مع pagination
        $results = $query->paginate($perPage);

        // إضافة إحصائيات البحث
        $searchStats = [
            'total_found' => $results->total(),
            'filters_applied' => array_filter([
                'category' => $request->input('category_id'),
                'brand' => $request->input('brand_id'),
                'unit' => $request->input('unit_id'),
                'active_only' => $request->boolean('is_active'),
                'exclude_expired' => $request->boolean('exclude_expired'),
                'price_min' => $request->input('price_min'),
                'price_max' => $request->input('price_max'),
                'sort' => $request->input('sort_by'),
            ])
        ];

        return response()->json([
            'success' => true,
            'message' => 'تم البحث بنجاح',
            'data' => new ProductCollection($results),
            'search_stats' => $searchStats,
        ]);
    }
}
