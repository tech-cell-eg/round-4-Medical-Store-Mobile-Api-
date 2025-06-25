<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    /**
     * عرض قائمة وحدات القياس
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $units = Unit::all();
        return response()->json([
            'message' => 'تم جلب وحدات القياس بنجاح',
            'data' => $units
        ]);
    }



    /**
     * إنشاء وحدة أو وحدات قياس جديدة
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // التحقق مما إذا كان الطلب يحتوي على مصفوفة من الوحدات
        $data = $request->all();
        $isMultiple = isset($data[0]) && is_array($data[0]);

        // إعداد قواعد التحقق
        $rules = [
            'name' => 'required|string|max:255|unique:units,name',
            'short_name' => 'required|string|max:50|unique:units,short_name',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ];

        $validator = Validator::make(
            $data,
            $isMultiple
                ? ['*' => 'array:' . implode(',', array_keys($rules))] + $rules
                : $rules
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطأ في التحقق من صحة البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $units = [];
        try {
            // بدء معاملة قاعدة البيانات
            DB::beginTransaction();

            if ($isMultiple) {
                // معالجة إدراج متعدد
                foreach ($data as $unitData) {
                    $units[] = Unit::create($unitData);
                }
                $message = 'تم إنشاء الوحدات بنجاح';
            } else {
                // معالجة إدراج وحدة واحدة
                $units[] = Unit::create($data);
                $message = 'تم إنشاء الوحدة بنجاح';
            }

            DB::commit();

            return response()->json([
                'message' => $message,
                'data' => $units
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'حدث خطأ أثناء إنشاء الوحدات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض وحدة قياس محددة
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
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
     *
     * @param Request $request
     * @param Unit $unit
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Unit $unit): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:units,name,' . $unit->id,
            'symbol' => 'sometimes|required|string|max:50|unique:units,symbol,' . $unit->id,
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
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
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
