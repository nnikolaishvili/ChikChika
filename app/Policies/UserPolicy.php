<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewProfile(?User $authUser, User $user): bool
    {
        // When user is logged in
        if ($authUser) {
            return $authUser->is($user) || !$user->is_private || $user->hasFollower($authUser->id);
        }

        // When user is guest
        return !$user->is_private;
    }
}
