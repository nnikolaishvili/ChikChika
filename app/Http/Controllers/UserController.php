<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function getProfile(User $user, Request $request)
    {
        $this->authorize('viewProfile', $user);

        $user->load(['tweets' => function ($query) {
            $query->orderByDesc('created_at');
        }, 'tweets.comments', 'tweets.likes']);

        $authUser = Auth::user();

        $data = [
            'authUser' => Auth::user(),
            'user' => $user
        ];

        if ($authUser) {
            $usersToFollow = User::whereDoesntHave('followers', function ($query) use ($authUser) {
                $query->where('follower_id', $authUser->id);
            })->where('id', '!=', $authUser->id)
                ->limit(5)
                ->get();

            $data['usersToFollow'] = $usersToFollow;
        }

        return view('profile', $data);
    }

    public function getSettings(User $user): View
    {
        $authUser = Auth::user();

        abort_unless($authUser->id == $user->id, 403);

        return view('settings', compact('user'));
    }

    public function updateSettings(User $user, UpdateProfileRequest $request): RedirectResponse
    {
        $authUser = Auth::user();

        abort_unless($authUser->id == $user->id, 403);

        $validated = $request->validated();

        if (isset($validated['image'])) {
            $path = 'images/users/' . Auth::id();
            $validated['image_url'] = Storage::disk('public')
                ->putFileAs($path, $validated['image'], $validated['image']->getClientOriginalName());
        }

        $validated['is_private'] = $validated['is_private'] ?? 0;

        $user->update($validated);

        return redirect()->route('dashboard');
    }

    public function follow(User $user, Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $follower = Auth::user();

            $follower->followings()->toggle($user->id);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'fail']);
    }
}
