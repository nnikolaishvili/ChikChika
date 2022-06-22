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

@push('scripts')
    <script>
        function makeUrlsClickable() {
            $('.tweet-text').each(function () {
                let text = $(this).html();
                let regex = /(\b(https?|ftp|file):\/\/[\-A-Z0-9+&@#\/%?=~_|!:,.;]*[\-A-Z09+&@#\/%=~_| ])/img
                let replaced_text = text.replace(regex, "<a href='$1' class='text-blue-500' target='_blank'>$1</a>");
                $(this).html(replaced_text);
            });
        }

        makeUrlsClickable();

        let tweets = $("#tweets-list");

        $(".show-more").click(function () {
            let url = $(this).attr('data-url');
            $.get(url, {page: +$(this).attr('data-next-page')}, function (response) {
                if ($('.hasMorePages').last().attr('data-has-more-pages') == 1) {
                    $('.show-more').attr('data-next-page', +$('.show-more').attr('data-next-page') + 1)
                    tweets.append(response);
                    makeUrlsClickable();
                    if ($('.hasMorePages').last().attr('data-has-more-pages') == 0) {
                        $('.show-more').fadeOut();
                    }
                }
            });
        });

        $(document).on('submit', '.delete-tweet-form', function ($e) {
            $e.preventDefault();
            if (confirm('Are you sure you want to delete this tweet?')) {
                let tweetId = $(this).attr('data-id');
                let url = $(this).attr('action');
                console.log($(this).attr('action'));

                $.post($(this).attr('action'), {
                    "_token": "{{ csrf_token() }}",
                    "id": tweetId
                }, function () {
                    $(document).find('.tweet-' + tweetId).remove();
                    if ($(document).find('#tweets-list').children('div').length === 0) {
                        $(document).find('#tweets-list').append('<div class="flex m-4 justify-center"><span class="ml-2">No tweets yet</span></div>')
                    }
                })
            }
        })
    </script>
@endpush
