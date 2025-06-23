<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * عرض قائمة وحدات القياس
     */
    public function index(): JsonResponse
    {
        $units = Unit::all();
        return response()->json(['data' => $units]);
    }

    /**
     * إنشاء وحدة قياس جديدة
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:units,name',
            'symbol' => 'required|string|max:50|unique:units,symbol',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        $unit = Unit::create($request->all());

        return response()->json([
            'message' => 'تم إنشاء وحدة القياس بنجاح',
            'data' => $unit
        ], 201);
    }

    /**
     * عرض وحدة قياس محددة
     */
    public function show(string $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'message' => 'وحدة القياس غير موجودة'
            ], 404);
        }


        return response()->json(['data' => $unit]);
    }

    /**
     * تحديث وحدة قياس محددة
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'message' => 'وحدة القياس غير موجودة'
            ], 404);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:units,name,' . $id,
            'symbol' => 'sometimes|required|string|max:50|unique:units,symbol,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        $unit->update($request->all());

        return response()->json([
            'message' => 'تم تحديث وحدة القياس بنجاح',
            'data' => $unit
        ]);
    }

    /**
     * حذف وحدة قياس
     */
    public function destroy(string $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'message' => 'وحدة القياس غير موجودة'
            ], 404);
        }


        // التحقق من وجود منتجات مرتبطة بهذه الوحدة قبل الحذف
        if ($unit->products()->count() > 0) {
            return response()->json([
                'message' => 'لا يمكن حذف وحدة القياس لأنها مرتبطة بمنتجات',
                'products_count' => $unit->products()->count()
            ], 422);
        }

        $unit->delete();

        return response()->json([
            'message' => 'تم حذف وحدة القياس بنجاح'
        ]);
    }
}
