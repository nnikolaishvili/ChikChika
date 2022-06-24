<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tweet\{Comment\StoreCommentRequest, StoreRequest};
use App\Models\{Comment, Tweet, User};
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TweetController extends Controller
{
    /**
     * Store Tweet
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        Auth::user()->tweets()->create([
            'text' => $request->validated('text')
        ]);

        return redirect()->back();
    }

    /**
     * Tweet detailed view
     *
     * @param Tweet $tweet
     * @return View
     * @throws AuthorizationException
     */
    public function show(Tweet $tweet): View
    {
        $this->authorize('viewTweet', $tweet);

        $tweet->load(['comments' => function ($query) {
            $query->orderByDesc('created_at');
        }, 'comments.user']);

        $authUser = Auth::user();

        $data = [
            'user' => $authUser,
            'tweet' => $tweet,
        ];

        if ($authUser) {
            $usersToFollow = User::whereDoesntHave('followers', function ($query) use ($authUser) {
                $query->where('follower_id', $authUser->id);
            })->where('id', '!=', $authUser->id)
                ->limit(5)
                ->get();

            $data['usersToFollow'] = $usersToFollow;
        }

        return view('tweets.show', $data);
    }

    /**
     * Tweet destroy
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $deleted = Tweet::findOrFail($request->id)->delete();

            if ($deleted) {
                return response()->json(null);
            }
        }

        return response()->json(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Tweet $tweet
     * @param StoreCommentRequest $request
     * @return RedirectResponse
     */
    public function storeComment(Tweet $tweet, StoreCommentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $tweet->comments()->create([
            'user_id' => Auth::id(),
            'text' => $validated['text']
        ]);

        return redirect()->back();
    }

    public function destroyComment(Tweet $tweet, Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->back();
    }
}
