<?php

namespace App\Policies;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TweetPolicy
{
    use HandlesAuthorization;

    public function viewTweet(?User $authUser, Tweet $tweet): bool
    {
        // When user is logged in
        if ($authUser) {
            return $authUser->is($tweet->user) || !$tweet->user->is_private || $tweet->user->hasFollower($authUser->id);
        }

        // When user is guest
        return !$tweet->user->is_private;
    }
}
