@extends('layouts.app')

@section('content')
    <div class="bg-neutral-900">
        <div class="flex">

            {{-- nagivation --}}
            <x-menu :user="$user"></x-menu>

            <div class="w-full p-5 border border-gray-600 h-auto border-t-0 border-b-0">
                <div class="max-w-md mb-5">
                    <h1 class="text-lg mb-5 font-bold">Notifications <i class="fa-solid fa-bell"></i></h1>

                    <div>
                        @forelse ($user->notifications as $notification)
                            <div class="alert shadow-lg mb-4 w-full">
                                <div class="flex w-full">
                                    @switch($notification->type)
                                        @case(\App\Notifications\UserTweeted::class)
                                        @php
                                            $user = \App\Models\User::find($notification->data['following_id'])
                                        @endphp
                                        <x-notification-text :route="route('tweet.show', $notification->data['tweet_id'])"
                                                             :user="$user"
                                                             :text="'The user has just tweeted.'">
                                        </x-notification-text>
                                        @break

                                        @case(\App\Notifications\UserHasBenFollowed::class)
                                        @php
                                            $follower = \App\Models\User::find($notification->data['follower_id']);
                                        @endphp
                                        <x-notification-text :route="route('profile', $follower->username)"
                                                             :user="$follower"
                                                             :text="'The user has just followed you.'">
                                        </x-notification-text>
                                        @break

                                        @case(\App\Notifications\UserHasBenUnfollowed::class)
                                        @php
                                            $follower = \App\Models\User::find($notification->data['follower_id']);
                                        @endphp
                                        <x-notification-text :route="route('profile', $follower->username)"
                                                             :user="$follower"
                                                             :text="'The user has just unfollowed you.'"
                                        ></x-notification-text>
                                        @break

                                        @case(\App\Notifications\LikedTweet::class)
                                        @php
                                            $user = \App\Models\User::find($notification->data['user_id']);
                                        @endphp
                                        <x-notification-text :route="route('tweet.show', $notification->data['tweet_id'])"
                                                             :user="$user"
                                                             :text="'The user has just liked your tweet.'"
                                        ></x-notification-text>
                                        @break

                                        @case(\App\Notifications\CommentedTweet::class)
                                        @php
                                            $user = \App\Models\User::find($notification->data['user_id']);
                                        @endphp
                                        <x-notification-text :route="route('tweet.show', $notification->data['tweet_id'])"
                                                             :user="$user"
                                                             :text="'The user has just liked commented on your tweet.'"
                                        ></x-notification-text>
                                        @break
                                    @endswitch
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24"
                                     class="stroke-info flex-shrink-0 w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="ml-3">
                                    <div class="text-xs">No notifications yet.</div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
