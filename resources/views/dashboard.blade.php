@extends('layouts.app')

@section('content')
    <div class="bg-neutral-900">
        <div class="flex">
            <div class="w-2/5 text-white h-12 pl-32 py-4 h-auto h-screen">
                <div class="h-12 w-12 text-center">
                    <a href="{{ route('dashboard', $user->username) }}">
                        <i class="fa-solid fa-dove text-3xl mt-3"></i>
                    </a>
                </div>

                <nav class="mt-5 pr-3">
                    <a href="{{ route('dashboard', $user->username) }}"
                       class="group flex items-center p-3 rounded-full hover:bg-blue-800 hover:text-blue-300">
                        <i class="fa-solid fa-house text-white text-2xl"></i> <span class="ml-3">Home</span>
                    </a>
                    <a href="#"
                       class="mt-1 group flex items-center p-3 rounded-full hover:bg-blue-800 hover:text-blue-300">
                        <i class="fa-solid fa-bell text-white text-2xl"></i> <span class="ml-3">Notifications</span>
                    </a>
                    <a href="#"
                       class="mt-1 group flex items-center p-3 rounded-full hover:bg-blue-800 hover:text-blue-300">
                        <i class="fa-solid fa-user text-white text-2xl"></i> <span class="ml-3">Profile</span>
                    </a>

                    <button
                        class="btn btn-wide bg-blue-600 w-full mt-5 hover:bg-blue-500 text-white py-2 px-4 rounded-full">
                        Tweet
                    </button>
                </nav>

                <div class="flex-shrink-0 flex hover:bg-blue-00 rounded-full p-4 mt-12 mr-2">
                    <a href="#" class="flex-shrink-0 group w-full">
                        <div class="flex items-center justify-between w-full">
                            <div class="flex">
                                <div>
                                    <img class="inline-block h-10 w-10 rounded-full"
                                         src="{{ $user->imageUrl() }}"
                                         alt=""/>
                                </div>
                                <div class="ml-3">
                                    <p class="text-base leading-6 text-white">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-sm leading-5 text-gray-400 group-hover:text-gray-300 transition ease-in-out duration-150">
                                        {{ "@" . $user->username }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <div class="text-center">
                                        <button type="submit" class="text-sm text-gray-600 hover:text-white">
                                            Log out
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="w-3/5 border border-gray-600 h-auto border-t-0 border-b-0">
                <div class="flex">
                    <div class="flex-1 m-2">
                        <h2 class="px-4 py-2 text-xl text-white">Home</h2>
                    </div>
                </div>

                <form action="{{ route('tweet.store') }}" method="post">
                    @csrf

                    <div class="flex px-4 mb-2">
                        <div class="m-2 w-10 py-1">
                            <img class="inline-block h-10 w-10 rounded-full" src="{{ $user->imageUrl() }}" alt=""/>
                        </div>
                        <div class="flex-1 px-2 mt-2">
                            <textarea class="textarea textarea-bordered w-full" rows="2" cols="50"
                                      placeholder="What's happening?" name="text"></textarea>
                            @error('text')
                            <div
                                class="alert-danger bg-red-200 p-1 rounded-full text-center">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <div class="w-64 px-2">
                            <div class="flex items-center">
                                <div class="flex-1 text-center px-1 py-1 m-2">
                                    <i class="fa-solid fa-image text-blue-400 text-lg"></i>
                                </div>

                                <div class="flex-1 text-center px-1 py-1 m-2">
                                    <i class="fa-solid fa-square-poll-vertical text-blue-400 text-lg"></i>
                                </div>

                                <div class="flex-1 text-center px-1 py-1 m-2">
                                    <i class="fa-solid fa-face-smile text-blue-400 text-lg"></i>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info btn-md rounded-full text-white mr-8">Tweet</button>
                    </div>
                </form>

                <hr class="border-gray-600">

                @forelse($tweets as $tweet)
                    <div class="flex flex-shrink-0 p-4 pb-0">
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center ml-3">
                                <img class="inline-block h-10 w-10 rounded-full"
                                     src="{{ $tweet->user->imageUrl() }}"
                                     alt="profile-image"/>
                                <div class="ml-3">
                                    <p class="text-base leading-6 text-white">
                                        {{ $tweet->user->name }}
                                        <span
                                            class="text-sm leading-5 text-gray-400 group-hover:text-gray-300 transition ease-in-out duration-150">
                                {{ '@' . $tweet->user->username }} | {{ $tweet->created_at->format('d M') }}
                              </span>
                                    </p>
                                </div>
                            </div>
                            <div class="text-red-400">
                                @if ($tweet->user_id == $user->id)
                                    <form action="{{ route('tweet.destroy', $tweet->id) }}" method="post"
                                          id="delete-tweet-form">
                                        @csrf
                                        @method('delete')

                                        <div class="dropdown">
                                            <label tabindex="0" class="btn btn-circle btn-outline btn-xs">...</label>
                                            <ul tabindex="0"
                                                class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                                <li>
                                                    <button type="submit"
                                                            onclick="return confirm('Are you sure you want to delete this tweet?');">
                                                        <i class="fa-solid fa-trash text-red-500"></i> Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="pl-16 pr-5">
                        <p class="text-base width-auto text-white flex-shrink break-all">
                            {{ $tweet->text }}
                        </p>

                        <div class="flex">
                            <div class="w-full">
                                <div class="flex items-center">
                                    <div class="flex-1 justify-start text-left">
                                        <a href="{{ route('tweet.show', $tweet->id) }}"
                                           class="btn btn-sm">
                                            <i class="fa-solid fa-comment text-xl"></i>
                                            <span class="ml-2">{{ $tweet->comments->count() }}</span>
                                        </a>
                                    </div>
                                    <div class="flex-1 py-2 m-2">
                                        <button
                                            class="btn btn-sm">
                                            {{--                                        <i class="fa-solid fa-heart text-xl"></i>--}}
                                            <i class="fa-regular fa-heart text-xl"></i>
                                            <span class="ml-2">{{ $tweet->likes->count() }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="border-gray-600">
                @empty
                    <div class="flex m-4 justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             class="stroke-info flex-shrink-0 w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="ml-2">No tweets yet</span>
                    </div>
                @endforelse

                @if ($tweets->currentPage() < $tweets->lastPage())
                    <div class="flex justify-center m-2">
                        <button class="btn btn-md">
                            Show more
                        </button>
                    </div>
                @endif
            </div>

            <div class="w-2/5 h-12">
                <div class="relative text-gray-300 w-80 p-5 pb-0 mr-16">
                    <input type="search" placeholder="Search Chikchika"
                           class="input input-bordered input-info w-full max-w-xs"/>
                </div>

                <div class="max-w-sm rounded-lg bg-blue-800 overflow-hidden shadow-lg m-4 mr-20">
                    <div class="flex">
                        <div class="flex-1 m-2">
                            <h2 class="px-4 py-2 text-lg w-48 text-white">Who to follow</h2>
                        </div>
                    </div>


                    <hr class="border-gray-600">

                    <!-- people to follow -->

                    <div class="flex flex-shrink-0">
                        <div class="flex-1 ">
                            <div class="flex items-center w-48">
                                <div>
                                    <img class="inline-block h-10 w-auto rounded-full ml-4 mt-2"
                                         src="{{asset('images/default_profile_400x400.png')}}"
                                         alt=""/>
                                </div>
                                <div class="ml-3 mt-3">
                                    <p class="text-base leading-6 text-white">
                                        Random person
                                    </p>
                                    <p class="text-sm leading-5 text-gray-400 group-hover:text-gray-300 transition ease-in-out duration-150">
                                        @Rand
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div class="flex-1 px-4 py-2 m-2">
                            <a href="" class=" float-right">
                                <button
                                    class="bg-transparent hover:bg-blue-500 text-white  hover:text-white py-2 px-4 border border-white hover:border-transparent rounded-full">
                                    Follow
                                </button>
                            </a>
                        </div>
                    </div>

                    <hr class="border-gray-600">

                    <!--show more-->

                    <div class="flex">
                        <div class="flex-1 p-4">
                            <h2 class="px-4 ml-2 w-48 text-blue-400">Show more</h2>
                        </div>
                    </div>

                </div>

                <div class="flow-root m-6 inline">
                    <div class="flex-2">
                        <p class="text-sm leading-6 text-gray-600"> © 2022 Chikchika</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
