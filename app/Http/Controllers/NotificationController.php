<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification; // tu modelo de notificaciones
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationController extends Controller
{


    public function fake()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->back()->with('error', 'Debes estar autenticado para generar notificaciones de prueba.');
        }

        $n = rand(3, 7);
        for ($i = 0; $i < $n; $i++) {
            Notification::create([
                'user_id' => $user->id,
                'message' => \Faker\Factory::create()->sentence(),
                'read' => false,
                'created_at' => now()->subMinutes(rand(0, 600)),
            ]);
        }

        return redirect()->route('notifications.show')->with('success', "$n notificaciones de prueba creadas.");
    }
    // Muestra todas las notificaciones
    public function show()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    // Devuelve las nuevas notificaciones en JSON (para el navbar)
    public function get()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('read', false)  // <- Esta línea debe estar presente
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
