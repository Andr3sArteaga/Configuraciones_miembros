<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification as Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    public function fake()
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Debes estar autenticado para generar notificaciones de prueba.');
        }

        $n = rand(3, 7);

        for ($i = 0; $i < $n; $i++) {
            $user->notify(new \App\Notifications\TestNotification());
        }

        return redirect()->route('notifications.show')->with('success', "$n notificaciones de prueba creadas.");
    }

    public function show()
    {
        /** @var User|null $user */
        $user = Auth::user();

        $notifications = Notification::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->getKey())
            ->orderBy('created_at', 'desc')
            ->get();

        // Marcar todas como leídas
        Notification::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->getKey())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    public function get()
    {
        /** @var User|null $user */
        $user = Auth::user();
        $notifications = Notification::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->getKey())
            ->whereNull('read_at')  // para las no leídas
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $count = $notifications->count();

        $dropdownHtml = view('notifications.dropdown', compact('notifications'))->render();

        return response()->json([
            'label' => $count,
            'label_color' => $count > 0 ? 'danger' : 'success',
            'icon_color' => $count > 0 ? 'warning' : 'success',
            'dropdown' => $dropdownHtml,
        ]);
    }
}
