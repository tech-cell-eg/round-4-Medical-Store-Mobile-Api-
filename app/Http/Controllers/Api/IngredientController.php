<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    /**
     * عرض قائمة جميع المكونات
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingredients = Ingredient::all();
        return response()->json([
            'status' => 'success',
            'data' => $ingredients
        ], Response::HTTP_OK);
    }

    /**
     * تخزين مكون جديد في قاعدة البيانات
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'البيانات غير صالحة',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $ingredient = Ingredient::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم إنشاء المكون بنجاح',
            'data' => $ingredient
        ], Response::HTTP_CREATED);
    }

    /**
     * عرض المكون المحدد
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ingredient = Ingredient::find($id);
        
        if (!$ingredient) {
            return response()->json([
                'status' => 'error',
                'message' => 'المكون غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ingredient
        ], Response::HTTP_OK);
    }

    /**
     * تحديث المكون المحدد في قاعدة البيانات
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::find($id);
        
        if (!$ingredient) {
            return response()->json([
                'status' => 'error',
                'message' => 'المكون غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'البيانات غير صالحة',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $ingredient->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث المكون بنجاح',
            'data' => $ingredient
        ], Response::HTTP_OK);
    }

    /**
     * حذف المكون المحدد من قاعدة البيانات
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::find($id);
        
        if (!$ingredient) {
            return response()->json([
                'status' => 'error',
                'message' => 'المكون غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        $ingredient->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف المكون بنجاح'
        ], Response::HTTP_OK);
    }
}
