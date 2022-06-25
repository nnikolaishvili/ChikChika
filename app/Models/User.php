<?php

namespace App\Models;

use App\Notifications\QueuedVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'image_url',
        'is_private'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send queued email verification notification
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new QueuedVerifyEmail);
    }

    public function imageUrl(): string
    {
        return $this->image_url
            ? asset('/storage/' . $this->image_url)
            : asset('images/default_profile_400x400.png');
    }

    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follower_user', 'follower_id', 'following_id')
            ->using(FollowerUser::class)
            ->withTimestamps();
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follower_user', 'following_id', 'follower_id')
            ->using(FollowerUser::class)
            ->withTimestamps();
    }

    public function hasFollower($id)
    {
        return $this->followers()->where('users.id', $id)->first();
    }

    public function liked($tweet)
    {
        return (bool)$this->likes->where('tweet_id', $tweet->id)->first();
    }

    public function unlike($tweet)
    {
        return $this->likes()->where('tweet_id', $tweet->id)->delete();
    }

    public function like($tweet)
    {
        return $this->likes()->create(['tweet_id' => $tweet->id]);
    }

    public function isPrivate()
    {
        return $this->is_private;
    }

    /**
     * Scope a query to only include unfollowed users.
     *
     * @param Builder $query
     * @param User $authUser
     * @return Builder
     */
    public function scopeToFollow(Builder $query, User $authUser): Builder
    {
        return $query->whereDoesntHave('followers', function ($query) use ($authUser) {
            $query->where('follower_id', $authUser->id);
        })
            ->where('id', '!=', $authUser->id)
            ->limit(5);
    }

    /**
     * Scope a query to only include email verified users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }
}
