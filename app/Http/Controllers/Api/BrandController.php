<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\BrandCollection;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * عرض قائمة العلامات التجارية
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        
        $query = Brand::query();
        
        // البحث بالاسم أو الوصف
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // التصفية حسب الحالة
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        // إضافة عدد المنتجات
        if ($request->boolean('with_products_count', false)) {
            $query->withCount('products');
        }
        
        // الترتيب
        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc');
        
        if (in_array($sortBy, ['name', 'created_at', 'updated_at'])) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('name', 'asc');
        }
        
        $brands = $query->paginate($perPage);
        
        return new BrandCollection($brands);
    }

    /**
     * عرض تفاصيل علامة تجارية محددة
     */
    public function show(Request $request, Brand $brand)
    {
        // إضافة عدد المنتجات إذا طلب ذلك
        if ($request->boolean('with_products_count', false)) {
            $brand->loadCount('products');
        }
        
        // تحميل المنتجات المرتبطة إذا طلب ذلك
        if ($request->boolean('with_products', false)) {
            $brand->load('products');
        }
        
        return new BrandResource($brand);
    }

    /**
     * إنشاء علامة تجارية جديدة
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->except('logo');
        
        // إنشاء slug إذا لم يتم توفيره
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // معالجة رفع الشعار
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
            $data['logo'] = $logoPath;
        }
        
        // إضافة معلومات المستخدم الذي أنشأ العلامة التجارية
        if (auth('sanctum')->check()) {
            $data['created_by'] = auth('sanctum')->id();
            $data['updated_by'] = auth('sanctum')->id();
        }

        $brand = Brand::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'تم إنشاء العلامة التجارية بنجاح',
            'data' => new BrandResource($brand)
        ], Response::HTTP_CREATED);
    }

    /**
     * تحديث علامة تجارية محددة
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->except('logo');
        
        // تحديث slug إذا تم تغيير الاسم ولم يتم توفير slug
        if (isset($data['name']) && (!isset($data['slug']) || empty($data['slug']))) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // معالجة تحديث الشعار
        if ($request->hasFile('logo')) {
            // حذف الشعار القديم إذا كان موجودًا
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            
            $logoPath = $request->file('logo')->store('brands', 'public');
            $data['logo'] = $logoPath;
        }
        
        // إضافة معلومات المستخدم الذي قام بالتحديث
        if (auth('sanctum')->check()) {
            $data['updated_by'] = auth('sanctum')->id();
        }

        $brand->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث العلامة التجارية بنجاح',
            'data' => new BrandResource($brand)
        ], Response::HTTP_OK);
    }

    /**
     * حذف علامة تجارية محددة
     */
    public function destroy(Brand $brand)
    {
        // التحقق من وجود منتجات مرتبطة
        if ($brand->products()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يمكن حذف العلامة التجارية لأنها مرتبطة بمنتجات'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // حذف الشعار إذا كان موجودًا
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }
        
        $brand->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف العلامة التجارية بنجاح'
        ], Response::HTTP_OK);
    }
}
