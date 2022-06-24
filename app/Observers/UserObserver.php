<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updating(User $user)
    {
        if ($user->isDirty('image_url')) {
            if ($user->getOriginal('image_url')) {
                Storage::disk('public')->delete($user->getOriginal('image_url'));
            }
        }
    }
}
