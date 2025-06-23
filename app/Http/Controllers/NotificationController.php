<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $filter = $request->query('filter', 'all');

            $notifications = $user->notifications();

            if ($filter === 'unread') {
                $notifications = $notifications->whereNull('read_at');
            } elseif ($filter === 'read') {
                $notifications = $notifications->whereNotNull('read_at');
            }

            $notifications = $notifications->orderBy('created_at', 'desc')
                ->paginate(20);
            return response()->json([
                'status' => true,
                'message' => 'Notifications retrieved successfully.',
                'data' => [
                    'notifications' => $notifications->items(),
                    'pagination' => [
                        'current_page' => $notifications->currentPage(),
                        'last_page' => $notifications->lastPage(),
                        'per_page' => $notifications->perPage(),
                        'total' => $notifications->total(),
                        'has_more_pages' => $notifications->hasMorePages(),
                        'previous_page_url' => $notifications->previousPageUrl(),
                        'next_page_url' => $notifications->nextPageUrl(),
                    ],
                    'unread_count' => $user->unreadNotifications()->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    public function markAsRead(Request $request, string $notificationId): JsonResponse
    {
        try {
            $user = Auth::user();

            $notification = $user->notifications()->where('id', $notificationId)->first();

            if (!$notification) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }

            if ($notification->read_at) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notification is already marked as read.',
                ], 400);
            }

            $notification->markAsRead();

            return response()->json([
                'status' => true,
                'message' => 'Notification marked as read successfully.',
                'data' => [
                    'notification_id' => $notification->id,
                    'read_at' => $notification->read_at,
                    'unread_count' => $user->unreadNotifications()->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }



    public function markAllAsRead(): JsonResponse
    {
        try {
            $user = Auth::user();

            $unreadCount = $user->unreadNotifications()->count();

            if ($unreadCount === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'No unread notifications found.',
                ], 400);
            }

            $user->unreadNotifications->markAsRead();

            return response()->json([
                'status' => true,
                'message' => 'All notifications marked as read successfully.',
                'data' => [
                    'marked_count' => $unreadCount,
                    'unread_count' => 0
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }



    public function destroy(string $notificationId): JsonResponse
    {
        try {
            $user = Auth::user();

            $notification = $user->notifications()->where('id', $notificationId)->first();

            if (!$notification) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'status' => true,
                'message' => 'Notification deleted successfully.',
                'data' => [
                    'deleted_notification_id' => $notificationId,
                    'unread_count' => $user->unreadNotifications()->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
}
