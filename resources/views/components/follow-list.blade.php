<div class="max-w-sm rounded-lg bg-blue-800 overflow-hidden shadow-lg m-4 mr-20">
    <div class="flex">
        <div class="flex-1 m-2">
            <h2 class="px-4 py-2 text-lg w-48 text-white">Who to follow</h2>
        </div>
    </div>

    <hr class="border-gray-600">

    <!-- people to follow -->

    @forelse($usersToFollow as $userToFollow)
        <div class="flex flex-shrink-0">
            <div class="flex-1 ">
                <div class="flex items-center w-48">
                    <div>
                        <img class="inline-block h-10 w-auto rounded-full ml-4 mt-2"
                             src="{{asset('images/default_profile_400x400.png')}}"
                             alt=""/>
                    </div>
                    <div class="ml-3 mt-3">
                        <p class="text-base leading-6 text-white text-xs">
                            {{ $userToFollow->name }}
                        </p>
                        <p class="text-xs leading-5 text-gray-400 group-hover:text-gray-300 transition ease-in-out duration-150">
                            {{ "@" . $userToFollow->username }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex-1 px-4 py-2 m-2">
                <form action="{{ route('user.follow', $userToFollow->id) }}" method="post"
                      class="follow-user-form">
                    @csrf
                    <button type="submit" data-following-id="{{ $userToFollow->id }}" data-follow="1"
                            class="bg-transparent hover:bg-blue-500 text-white  hover:text-white py-2 px-4
                                    text-sm w-24 border border-white hover:border-transparent rounded-full">
                        follow
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="py-3 px-5 text-sm">
            No users to follow
        </div>
    @endforelse

    <hr class="border-gray-600">
</div>
