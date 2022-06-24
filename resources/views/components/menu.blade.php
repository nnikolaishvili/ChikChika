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
            <a href="{{ route('notifications') }}"
               class="mt-1 group flex items-center p-3 rounded-full hover:bg-blue-800 hover:text-blue-300">
                <i class="fa-solid fa-bell text-white text-2xl"></i>
                <span class="ml-3">Notifications
                    @php
                        $unreadNotificationsCount = $user->unreadNotifications()->count();
                    @endphp

                    @if ($unreadNotificationsCount)
                    <span class="ml-1 badge badge-secondary">{{ $unreadNotificationsCount }}</span>
                    @endif
                </span>
            </a>
            <a href="{{ route('profile', $user->username) }}"
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
            <a href="{{ route('profile', $user->username) }}" class="flex-shrink-0 group">
                <div class="flex">
                    <div>
                        <img class="inline-block h-10 w-10 rounded-full"
                             src="{{ $user->imageUrl() }}"
                             alt=""/>
                    </div>
                    <div class="ml-3">
                        <p class="text-base text-sm leading-6 text-white">
                            {{ $user->name }}
                        </p>
                        <p class="text-sm leading-5 text-gray-400 group-hover:text-gray-300 transition ease-in-out duration-150">
                            {{ "@" . $user->username }}
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
                                        Log out {{ "@" . $user->username }}
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
