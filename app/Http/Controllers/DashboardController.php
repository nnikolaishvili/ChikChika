<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard($username)
    {
        $user = User::where('username', $username)->first();

        abort_unless($user, 404);

        abort_unless($user->id == Auth::id(), 403);

        return view('welcome');
    }
}
