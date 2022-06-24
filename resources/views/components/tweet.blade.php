<div class="tweet tweet-{{ $tweet->id }}">
    <div class="flex flex-shrink-0 p-4 pb-0">
        <div class="flex items-center justify-between w-full">
            <a href="{{ route('profile', $tweet->user->username) }}">
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
            </a>

            <div class="text-red-400">
                @if ($tweet->user_id == auth()->id())
                    <form action="{{ route('tweet.destroy', $tweet->id) }}" method="post"
                          class="delete-tweet-form" data-id="{{ $tweet->id }}">
                        <div class="dropdown">
                            <label tabindex="0"
                                   class="btn btn-circle btn-outline btn-xs">...</label>
                            <ul tabindex="0"
                                class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                @if (isset($view))
                                    <li>
                                        <a href="{{ route('tweet.show', $tweet->id) }}" class="text-blue-300">
                                            <i class="fa-solid fa-trash"></i> View
                                        </a>
                                    </li>
                                @endif
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
        <p class="text-base pl-4 width-auto text-white flex-shrink break-all tweet-text">
            {{ $tweet->text }}
        </p>

        <div class="flex pl-4">
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
                        @auth
                            <form action="{{ route('tweet.like', $tweet) }}" class="like-form" method="post">
                                <button type="submit" data-is-liked="{{ $authUser->liked($tweet) ? 1 : 0 }}"
                                        class="btn btn-sm">
                                    @if ($authUser->liked($tweet))
                                        <i class="fa-solid fa-heart text-xl like-icon"></i>
                                    @else
                                        <i class="fa-regular fa-heart text-xl like-icon"></i>
                                    @endif
                                    <span class="ml-2 likes-count">{{ $tweet->likes->count() }}</span>
                                </button>
                            </form>
                        @endauth

                        @guest
                            <form action="{{ route('tweet.like', $tweet) }}" method="post">
                                @csrf

                                <button type="submit" class="btn btn-sm">
                                    <i class="fa-regular fa-heart text-xl like-icon"></i>
                                    <span class="ml-2 likes-count">{{ $tweet->likes->count() }}</span>
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="border-gray-600">
</div>
