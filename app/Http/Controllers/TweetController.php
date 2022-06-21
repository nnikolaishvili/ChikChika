<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tweet\StoreRequest;
use App\Models\Tweet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    public function store(StoreRequest $request): RedirectResponse
    {
        Auth::user()->tweets()->create([
            'text' => $request->validated('text')
        ]);

        return redirect()->back();
    }

    public function show(Tweet $tweet)
    {
        return view('tweets.show', [
            'user' => Auth::user(),
            'tweet' => $tweet
        ]);
    }

    public function destroy(Tweet $tweet): RedirectResponse
    {
        $tweet->delete();

        return redirect()->back();
    }
}
