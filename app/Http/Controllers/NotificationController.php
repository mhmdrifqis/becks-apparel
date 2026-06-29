<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index()
    {
        if (!Auth::check()) {
            return response()->json([]);
        }

        $notifications = Auth::user()->notifications()->take(5)->get()->map(function ($notif) {
            return [
                'id' => $notif->id,
                'data' => $notif->data,
                'read_at' => $notif->read_at,
                'created_at' => $notif->created_at->diffForHumans()
            ];
        });

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->find($id);
            if ($notification) {
                $notification->markAsRead();
            }
        }
        return response()->json(['success' => true]);
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
        }
        return response()->json(['success' => true]);
    }
}
