<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Scope a query to only include owned or following users tweets.
     *
     * @param Builder $query
     * @param User $user
     * @param Collection $userFollowingIds
     * @return Builder
     */
    public function scopeOwnedOrFollowings(Builder $query, User $user, Collection $userFollowingIds): Builder
    {
        return $query->where(function ($query) use ($user, $userFollowingIds) {
            $query->where('user_id', $user->id)
                ->orWhereIn('user_id', $userFollowingIds);
        });
    }

    /**
     * Scope a query to include tweets ordered by created at descending
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRecentOnTop(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }
}
