<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * User's dashboard
     *
     * @param Request $request
     * @return View
     */
    public function dashboard(Request $request): View
    {
        $user = Auth::user();
        $usersToFollow = User::toFollow($user)->get();
        $userFollowingIds = $user->followings()->pluck('users.id');

        $tweets = Tweet::with(['user', 'comments', 'likes'])
            ->ownedOrFollowings($user, $userFollowingIds)
            ->recentOnTop()
            ->paginate(10, page: $request->page);

        $data = [
            'authUser' => $user,
            'tweets' => $tweets,
            'usersToFollow' => $usersToFollow
        ];

        if ($request->ajax()) {
            return view('components.tweets', $data);
        }

        return view('dashboard', $data);
    }
}
