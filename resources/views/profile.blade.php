@extends('layouts.app')

@section('content')
    <div class="bg-neutral-900">
        <div class="flex">

            {{-- nagivation --}}
            <div class="w-2/5 text-white h-12 pl-32 py-4 h-auto h-screen">
                <div class="h-12 w-12 text-center">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-dove text-3xl mt-3"></i>
                    </a>
                </div>

                <nav class="mt-5 pr-3">
                    <a href="{{ route('dashboard') }}"
                       class="group flex items-center p-3 rounded-full hover:bg-blue-800 hover:text-blue-300">
                        <i class="fa-solid fa-house text-white text-2xl"></i> <span class="ml-3">Home</span>
                    </a>
                    @auth
                        <a href="#"
                           class="mt-1 group flex items-center p-3 rounded-full hover:bg-blue-800 hover:text-blue-300">
                            <i class="fa-solid fa-bell text-white text-2xl"></i> <span class="ml-3">Notifications</span>
                        </a>
                        <a href="{{ route('profile', $authUser->username) }}"
                           class="mt-1 group flex items-center p-3 rounded-full hover:bg-blue-800 hover:text-blue-300">
                            <i class="fa-solid fa-user text-white text-2xl"></i> <span class="ml-3">Profile</span>
                        </a>

                        <button
                            class="btn btn-wide bg-blue-600 w-full mt-5 hover:bg-blue-500 text-white py-2 px-4 rounded-full">
                            Tweet
                        </button>
                    @endauth
                </nav>
                @auth
                    <div class="flex-shrink-0 flex hover:bg-blue-00 rounded-full p-4 mt-12 mr-2">
                        <div class="flex items-center justify-between w-full">
                            <a href="{{ route('profile', $authUser->username) }}" class="flex-shrink-0 group">
                                <div class="flex">
                                    <div>
                                        <img class="inline-block h-10 w-10 rounded-full"
                                             src="{{ $authUser->imageUrl() }}"
                                             alt=""/>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-base text-sm leading-6 text-white">
                                            {{ $authUser->name }}
                                        </p>
                                        <p class="text-sm leading-5 text-gray-400 group-hover:text-gray-300 transition ease-in-out duration-150">
                                            {{ "@" . $authUser->username }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <div>
                                <div class="dropdown">
                                    <label tabindex="0"
                                           class="btn btn-circle btn-outline btn-xs">...</label>
                                    <ul tabindex="0"
                                        class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf

                                                <div class="text-center">
                                                    <button type="submit" class="text-sm hover:text-white">
                                                        Log out {{ "@" . $authUser->username }}
                                                    </button>
                                                </div>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>

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

@push('scripts')
    <script>
        $('.tweet-text').each(function () {
            let text = $(this).html();
            let regex = /(\b(https?|ftp|file):\/\/[\-A-Z0-9+&@#\/%?=~_|!:,.;]*[\-A-Z09+&@#\/%=~_| ])/img
            let replaced_text = text.replace(regex, "<a href='$1' class='text-blue-500' target='_blank'>$1</a>");
            $(this).html(replaced_text);
        });

        $(".follow-user-form").submit(function (e) {
            e.preventDefault();
            let followingId = $(this).find('button').attr('data-following-id');
            $.post($(this).attr('action'), {
                "_token": "{{ csrf_token() }}",
            }, function (response) {
                if (response.status === 'success') {
                    let followButton = $(".follow-user-form").find(`[data-following-id='${followingId}']`);
                    if (followButton.attr('data-follow') == 1) {
                        followButton.html('unfollow');
                        followButton.attr('data-follow', 0);
                    } else {
                        followButton.html('follow');
                        followButton.attr('data-follow', 1);
                    }
                }
            })
        });
    </script>
@endpush
