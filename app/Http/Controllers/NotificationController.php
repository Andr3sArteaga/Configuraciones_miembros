<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get user's notifications
     */
    public function index(Request $request)
    {
        $notifications = Notification::where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'notifications' => $notifications->items(),
                'unread_count' => Notification::where('usuario_id', Auth::id())
                    ->unread()
                    ->count(),
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, string $notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notificación marcada como leída'
            ]);
        }

        return redirect()->back()->with('success', 'Notificación marcada como leída');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        Notification::where('usuario_id', Auth::id())
            ->unread()
            ->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Todas las notificaciones marcadas como leídas'
            ]);
        }

        return redirect()->back()->with('success', 'Todas las notificaciones marcadas como leídas');
    }

    /**
     * Get unread count (for navbar badge)
     */
    public function unreadCount()
    {
        $count = Notification::where('usuario_id', Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
