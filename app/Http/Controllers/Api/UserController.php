<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function details(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function followings(): AnonymousResourceCollection
    {
        $followings = Auth::user()->followings()->get();

        return UserResource::collection($followings);
    }

    public function follows(): AnonymousResourceCollection
    {
        $followers = Auth::user()->followers()->get();

        return UserResource::collection($followers);
    }
}
