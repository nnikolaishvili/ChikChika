@extends('layouts.app')

@section('content')
    <div class="bg-neutral-900">
        <div class="flex">

            {{-- nagivation --}}
            <x-menu :user="$authUser"></x-menu>

            {{-- content --}}
            <div class="w-3/5 border border-gray-600 h-auto border-t-0 border-b-0">
                <div class="flex">
                    <div class="flex-1 m-2">
                        <h2 class="px-4 py-2 text-xl text-white">Profile</h2>
                    </div>
                </div>

                <div class="h-36 bg-gray-800 flex justify-end items-end pr-2 pb-2">
                    @auth
                        @if ($authUser->is($user))
                            <a href="{{ route('settings', $user->username) }}" class="btn btn-sm btn-outline-info">Set
                                up
                                profile</a>
                        @endif
                    @endauth
                </div>

                <div class="avatar ml-3 mb-10 top-36 absolute">
                    <div class="w-24 rounded-full">
                        <img class="show-image" src="{{ $user->ImageUrl() }}" alt="profile-image"/>
                    </div>
                </div>

                <div class="p-5 mt-5">
                    <div>
                        <div class="text-white">{{ $user->name }}</div>
                        <div>{{ "@" . $user->username }}</div>
                        <div class="text-sm">Joined: {{ $user->created_at->format('d M Y') }}</div>
                        <div class="text-sm">Following: <span
                                class="text-white">{{ $user->followings()->count() }}</span> . Follower: <span
                                class="text-white">{{ $user->followers()->count() }}</span></div>
                    </div>
                </div>

                <hr class="border-gray-600">

                @forelse($user->tweets as $tweet)
                    <x-tweet :user="$user" :tweet="$tweet" :view="true"></x-tweet>
                @empty
                    <div class="flex m-4 justify-center no-tweets">
                        <span class="ml-2">No tweets yet</span>
                    </div>
                @endforelse
            </div>

            <div class="w-2/5 h-12">
                @auth
                    <x-follow-list :usersToFollow="$usersToFollow"></x-follow-list>
                @endauth

                <div class="flow-root m-6 inline">
                    <div class="flex-2">
                        <p class="text-sm leading-6 text-gray-600"> © 2022 Chikchika</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
