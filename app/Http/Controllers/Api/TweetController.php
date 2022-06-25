<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{Tweet\Comment\StoreCommentRequest, Tweet\StoreRequest};
use App\Http\Resources\{CommentResource, TweetResource};
use App\Models\Tweet;
use App\Notifications\{CommentedTweet, UserTweeted};
use Illuminate\Http\{JsonResponse, Request, Resources\Json\AnonymousResourceCollection};
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    public function tweets(Request $request): AnonymousResourceCollection
    {
        $user = Auth::user();

        $userFollowingIds = $user->followings()->pluck('users.id');

        $tweets = Tweet::with(['user', 'comments'])
            ->withCount('likes')
            ->ownedOrFollowings($user, $userFollowingIds)
            ->recentOnTop()
            ->paginate(10, page: $request->page);

        return TweetResource::collection($tweets->items());
    }

    public function store(StoreRequest $request): TweetResource
    {
        $user = Auth::user();

        $tweet = $user->tweets()->create([
            'text' => $request->validated('text')
        ]);

        $followers = $user->followers()->get();

        foreach ($followers as $follower) {
            $follower->notify(new UserTweeted($user, $follower, $tweet));
        }

        return new TweetResource($tweet);
    }

    public function show(Tweet $tweet): TweetResource
    {
        $this->authorize('viewTweet', $tweet);

        $tweet->load(['comments' => function ($query) {
            $query->recentOnTop();
        }, 'comments.user'])
            ->loadCount('likes');

        return new TweetResource($tweet);
    }

    public function replies(Tweet $tweet): AnonymousResourceCollection
    {
        return CommentResource::collection($tweet->comments()->with('user', 'tweet')->recentOnTop()->get());
    }

    public function like(Tweet $tweet): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser->liked($tweet)) {
            $authUser->like($tweet);

            return response()->json([
                'status' => 'Successfully liked tweet.',
                'tweet' => new TweetResource($tweet)
            ]);
        }

        return response()->json([
            'status' => 'Tweet is already liked.'
        ]);
    }

    public function unlike(Tweet $tweet): JsonResponse
    {
        $authUser = Auth::user();

        if ($authUser->liked($tweet)) {
            $authUser->unlike($tweet);

            return response()->json([
                'status' => 'Successfully unliked tweet.',
                'tweet' => new TweetResource($tweet)
            ]);
        }

        return response()->json([
            'status' => 'Tweet is already unliked.'
        ]);
    }

    public function reply(Tweet $tweet, StoreCommentRequest $request): CommentResource
    {
        $validated = $request->validated();

        $comment = $tweet->comments()->create([
            'user_id' => Auth::id(),
            'text' => $validated['text']
        ]);

        if (Auth::user()->isNot($tweet->user)) {
            $tweet->user->notify(new CommentedTweet(Auth::user(), $tweet));
        }

        return new CommentResource($comment->load('user', 'tweet'));
    }
}
