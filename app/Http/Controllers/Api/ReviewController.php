<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * عرض قائمة التقييمات لمنتج معين
     *
     * @param int $productId
     * @return \Illuminate\Http\Response
     */
    public function index($productId)
    {
        $reviews = Review::with(['user', 'product'])
            ->where('product_id', $productId)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reviews,
            'average_rating' => Review::getAverageRating($productId),
            'total_reviews' => $reviews->count()
        ], Response::HTTP_OK);
    }

    /**
     * تخزين تقييم جديد في قاعدة البيانات
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $productId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $productId)
    {
        // التحقق من وجود المنتج
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'المنتج غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        // التحقق مما إذا كان المستخدم قد قيم هذا المنتج من قبل
        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return response()->json([
                'status' => 'error',
                'message' => 'لقد قمت بتقييم هذا المنتج مسبقاً'
            ], Response::HTTP_BAD_REQUEST);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'reviewer_name' => 'nullable|string|max:255',
            'review_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'البيانات غير صالحة',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $reviewData = $request->all();
        $reviewData['product_id'] = $productId;
        $reviewData['user_id'] = Auth::id();
        
        // إذا لم يتم إدخال اسم المراجع، استخدم اسم المستخدم الحالي
        if (empty($reviewData['reviewer_name']) && Auth::check()) {
            $reviewData['reviewer_name'] = Auth::user()->name;
        }

        $review = Review::create($reviewData);

        // تحديث متوسط التقييم للمنتج
        $product->update([
            'average_rating' => $product->reviews()->avg('rating')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم إضافة التقييم بنجاح',
            'data' => $review->load('user')
        ], Response::HTTP_CREATED);
    }

    /**
     * عرض تقييم محدد
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $review = Review::with(['user', 'product'])->find($id);
        
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'التقييم غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $review
        ], Response::HTTP_OK);
    }

    /**
     * تحديث التقييم المحدد في قاعدة البيانات
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'التقييم غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        // التحقق من صلاحيات المستخدم
        if (Auth::id() !== $review->user_id && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'غير مصرح لك بتعديل هذا التقييم'
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'reviewer_name' => 'nullable|string|max:255',
            'review_date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'البيانات غير صالحة',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $review->update($request->all());

        // تحديث متوسط التقييم للمنتج
        $product = $review->product;
        $product->update([
            'average_rating' => $product->reviews()->avg('rating')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث التقييم بنجاح',
            'data' => $review->load('user')
        ], Response::HTTP_OK);
    }

    /**
     * حذف التقييم المحدد من قاعدة البيانات
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::find($id);
        
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'التقييم غير موجود'
            ], Response::HTTP_NOT_FOUND);
        }

        // التحقق من صلاحيات المستخدم
        if (Auth::id() !== $review->user_id && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'غير مصرح لك بحذف هذا التقييم'
            ], Response::HTTP_FORBIDDEN);
        }

        $product = $review->product;
        $review->delete();

        // تحديث متوسط التقييم للمنتج
        $product->update([
            'average_rating' => $product->reviews()->avg('rating')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف التقييم بنجاح'
        ], Response::HTTP_OK);
    }
}
