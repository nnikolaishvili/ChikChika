<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Notifications view
     *
     * @return View
     */
    public function index(): View
    {
        $authUser = Auth::user();

        $authUser->unreadNotifications()->update(['read_at' => now()]);

        return view('notifications.index', compact('authUser'));
    }
}
