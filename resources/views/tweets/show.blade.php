@extends('layouts.app')

@section('content')
    <div class="bg-neutral-900">
        <div class="flex">

            {{-- nagivation --}}
            <x-menu :user="$user"></x-menu>

            <div class="w-3/5 border border-gray-600 h-auto border-t-0 border-b-0">
                <div class="flex">
                    <div class="flex m-2">
                        <a href="{{ route('dashboard', $user->username) }}"><i
                                class="fa-solid fa-arrow-left pl-4 py-2 text-xl text-white"></i></a>
                        <h2 class="px-4 py-2 text-xl text-white">Tweet</h2>
                    </div>
                </div>

                <hr class="border-gray-600">

                <x-tweet :user="$user" :tweet="$tweet"></x-tweet>

                <form action="{{ route('tweet.comment.store', $tweet->id) }}" method="post">
                    @csrf

                    <div class="flex px-4 mb-2">
                        <div class="m-2 w-10 py-1">
                            <img class="inline-block h-10 w-10 rounded-full" src="{{ $user->imageUrl() }}" alt=""/>
                        </div>
                        <div class="flex-1 px-2 mt-2">
                            <textarea class="textarea textarea-bordered w-full" rows="2" cols="50"
                                      placeholder="Tweet your reply" name="text"></textarea>
                            @error('text')
                            <div
                                class="alert-danger bg-red-200 p-1 rounded-full text-center">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex justify-center items-center mb-3">
                            <button type="submit" class="btn btn-info btn-md rounded-full text-white">Reply</button>
                        </div>
                    </div>
                </form>

                <hr class="border-gray-600">

                <div id="comments-list">
                    @forelse($tweet->comments as $comment)
                        <div class="comment comment-{{ $comment->id }}">
                            <div class="flex flex-shrink-0 p-4 pb-0">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center ml-3">
                                        <img class="inline-block h-10 w-10 rounded-full"
                                             src="{{ $comment->user->imageUrl() }}"
                                             alt="profile-image"/>
                                        <div class="ml-3">
                                            <p class="text-base leading-6 text-white">
                                                {{ $comment->user->name }}
                                                <span
                                                    class="text-sm leading-5 text-gray-400 group-hover:text-gray-300 transition ease-in-out duration-150">
                                            {{ '@' . $comment->user->username }} | {{ $comment->created_at->format('d M') }}
                                          </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-red-400">
                                        @if ($comment->user_id == $user->id)
                                            <form
                                                action="{{ route('tweet.comment.destroy', ['tweet' => $tweet->id, 'comment' => $comment->id]) }}"
                                                method="post"
                                                class="delete-comment-form" data-id="{{ $tweet->id }}">
                                                @csrf

                                                <div class="dropdown">
                                                    <label tabindex="0"
                                                           class="btn btn-circle btn-outline btn-xs">...</label>
                                                    <ul tabindex="0"
                                                        class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                                        <li>
                                                            <button type="submit">
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
                            <div class="pl-16 pr-5 mt-1">
                                <p class="text-base pl-4 pb-4 width-auto text-white flex-shrink break-all tweet-text">
                                    {{ $comment->text }}
                                </p>

                            </div>
                            <hr class="border-gray-600">
                        </div>

                    @empty
                        <div class="flex m-4 justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 class="stroke-info flex-shrink-0 w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="ml-2">No replies yet</span>
                        </div>
                    @endforelse
                </div>

            </div>

            <!--right menu-->
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
        $('.tweet-text').each(function () {
            let text = $(this).html();
            let regex = /(\b(https?|ftp|file):\/\/[\-A-Z0-9+&@#\/%?=~_|!:,.;]*[\-A-Z09+&@#\/%=~_| ])/img
            let replaced_text = text.replace(regex, "<a href='$1' class='text-blue-500' target='_blank'>$1</a>");
            $(this).html(replaced_text);
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
                    window.location = "{{ config('app.url') }}";
                })
            }
        })
    </script>
@endpush
