<a href="{{ $route }}"
   class="w-full">
    <div class="flex">
        <div class="mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24"
                 class="stroke-info flex-shrink-0 w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <div class="mb-2">
                <img class="inline-block h-10 w-10 rounded-full"
                     src="{{ $user->imageUrl() }}"
                     alt=""/>
            </div>
            <div class="text-white text-lg">{{ $user->name }}</div>
            <div class="text-xs">{{ $text }}</div>
        </div>
    </div>
</a>
