<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUserController extends Controller
{
    /**
     * Login view
     *
     * @return View
     */
    public function view(): View
    {
        return view('auth.login');
    }

    /**
     * Log user in
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if (!Auth::attempt($request->getCredentials(), $request->has('remember'))) {
            return redirect()->route('login')->withErrors([
                'email' => trans('auth.failed')
            ])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Log user out
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
