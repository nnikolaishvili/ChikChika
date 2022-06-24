<?php

namespace App\Observers;

use App\Models\FollowerUser;
use App\Notifications\UserHasBenFollowed;
use App\Notifications\UserHasBenUnfollowed;

class FollowerUserObserver
{
    /**
     * Handle the "following" event.
     *
     * @param FollowerUser $followerUser
     * @return void
     */
    public function created(FollowerUser $followerUser)
    {
        $followerUser->following->notify(new UserHasBenFollowed($followerUser->follower_id, $followerUser->following_id));
    }

    /**
     * Handle the "unfollowing" event.
     *
     * @param FollowerUser $followerUser
     * @return void
     */
    public function deleting(FollowerUser $followerUser)
    {
        $followerUser->following->notify(new UserHasBenUnfollowed($followerUser->follower_id, $followerUser->following_id));
    }
}
