<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FollowerUser extends Pivot
{
    use HasFactory;

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id', 'id');
    }

    public function following(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_id', 'id');
    }
}
