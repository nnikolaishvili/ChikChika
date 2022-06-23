<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateProfileRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getProfile(): View
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validated();

        if (isset($validated['image'])) {
            $path = 'images/users/' . Auth::id();
            $validated['image_url'] = Storage::disk('public')->putFileAs($path, $validated['image'], $validated['image']->getClientOriginalName());
        }

        $validated['is_private'] = $validated['is_private'] ?? 0;

        $user->update($validated);

        return redirect()->back();
    }
}
