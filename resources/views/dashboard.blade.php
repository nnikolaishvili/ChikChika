@extends('layouts.app')

@section('content')
    <div class="bg-neutral-900">
        <div class="flex">

            {{-- nagivation --}}
            <x-menu :user="$user"></x-menu>

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

                <div id="tweets-list">
                    <x-tweets :user="$user" :tweets="$tweets"></x-tweets>
                </div>

                @if ($tweets->hasMorePages() && $tweets->currentPage() < $tweets->lastPage())
                    <div class="flex justify-center m-2">
                        <button class="btn btn-md show-more" data-next-page="{{ $tweets->currentPage() + 1 }}"
                                data-url="{{ url()->current() }}">
                            Show more
                        </button>
                    </div>
                @endif
            </div>

            <div class="w-2/5 h-12">
                <x-follow-list :usersToFollow="$usersToFollow"></x-follow-list>

                <div class="flow-root m-6 inline">
                    <div class="flex-2">
                        <p class="text-sm leading-6 text-gray-600"> © 2022 Chikchika</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
