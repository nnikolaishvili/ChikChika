<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $usersToFollow = User::whereDoesntHave('followers', function ($query) use ($user) {
            $query->where('follower_id', $user->id);
        })->where('id', '!=', $user->id)
            ->limit(5)
            ->get();

        $userFollowingIds = $user->followings()->pluck('users.id');

        $tweets = Tweet::with(['user', 'comments', 'likes'])
            ->where(function ($query) use ($user, $userFollowingIds) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('user_id', $userFollowingIds);
            })
            ->orderByDesc('created_at')
            ->paginate(10, page: $request->page);

        $data = [
            'user' => $user,
            'tweets' => $tweets,
            'usersToFollow' => $usersToFollow
        ];

        if ($request->ajax()) {
            return view('components.tweets', $data);
        }

        return view('dashboard', $data);
    }
}
