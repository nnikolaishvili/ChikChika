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
     * Get profile view
     *
     * @param User $user
     * @param Request $request
     * @return View
     * @throws AuthorizationException
     */
    public function getProfile(User $user, Request $request): View
    {
        $this->authorize('viewProfile', $user);

        $user->load(['tweets' => function ($query) {
            $query->recentOnTop();
        }, 'tweets.comments', 'tweets.likes', 'tweets.user']);

        $authUser = Auth::user();

        $data = [
            'authUser' => $authUser,
            'user' => $user,
        ];

        if ($authUser) {
            $data['usersToFollow'] = User::toFollow($authUser)->get();
        }

        return view('user.profile', $data);
    }

    /**
     * Get set profile view
     *
     * @param User $user
     * @return View
     */
    public function getSettings(User $user): View
    {
        $authUser = Auth::user();

        abort_unless($authUser->id == $user->id, 403);

        return view('user.settings', compact('authUser'));
    }

    /**
     * Update user's profile information
     *
     * @param User $user
     * @param UpdateProfileRequest $request
     * @return RedirectResponse
     */
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

    /**
     * Follow or unfollow user
     *
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */
    public function toggleFollow(User $user, Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $follower = Auth::user();

            $follower->followings()->toggle($user->id);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'fail']);
    }
}
