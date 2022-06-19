<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Register;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, Hash};

class RegisterController extends Controller
{
    /**
     * Registration view
     *
     * @return View
     */
    public function view(): View
    {
        return view('auth.register');
    }

    /**
     * Registration
     *
     * @param Register $request
     * @return RedirectResponse
     */
    public function store(Register $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard', $user->username);
    }
}
