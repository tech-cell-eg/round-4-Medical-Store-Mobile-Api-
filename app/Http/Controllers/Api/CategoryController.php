<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        
        $query = Category::query();
        
        // تحميل العلاقات
        if ($request->has('with_children')) {
            $query->with('children');
        }
        
        // الحصول على الفئات الرئيسية فقط
        if ($request->boolean('root_only', false)) {
            $query->whereNull('parent_id');
        }
        
        // البحث بالاسم
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
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
        $query->orderBy($sortBy, $sortDir);
        
        $categories = $query->paginate($perPage);
        
        return new CategoryCollection($categories);
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
            'name' => 'required|string|unique:categories|max:255',
            'description' => 'nullable|string',
            'slug' => 'required|string|unique:categories|max:255',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->except('image');
        
        // معالجة رفع الصورة
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image_url'] = $imagePath;
        }

        $category = Category::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'تم إنشاء الفئة بنجاح',
            'data' => new CategoryResource($category)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Category $category)
    {
        // تحميل العلاقات حسب الطلب
        if ($request->boolean('with_children', false)) {
            $category->load('children');
        }
        
        if ($request->boolean('with_parent', false)) {
            $category->load('parent');
        }
        
        if ($request->boolean('with_products_count', false)) {
            $category->loadCount('products');
        }
        
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:categories,name,' . $category->id . '|max:255',
            'description' => 'nullable|string',
            'slug' => 'sometimes|required|string|unique:categories,slug,' . $category->id . '|max:255',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->except('image');
        
        // معالجة رفع الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($category->image_url) {
                Storage::disk('public')->delete($category->image_url);
            }
            
            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image_url'] = $imagePath;
        }

        $category->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث الفئة بنجاح',
            'data' => new CategoryResource($category)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // التحقق من وجود فئات فرعية
        if ($category->children()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يمكن حذف الفئة لأنها تحتوي على فئات فرعية'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // التحقق من وجود منتجات مرتبطة
        if ($category->products()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يمكن حذف الفئة لأنها تحتوي على منتجات مرتبطة'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // حذف الصورة إذا كانت موجودة
        if ($category->image_url) {
            Storage::disk('public')->delete($category->image_url);
        }
        
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف الفئة بنجاح'
        ], Response::HTTP_OK);
    }
}
