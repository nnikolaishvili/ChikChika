<input type="hidden" class="hasMorePages" data-has-more-pages="{{ $tweets->currentPage() != $tweets->lastPage() ? 1 : 0 }}">
@forelse($tweets as $tweet)
    <x-tweet :authUser="$authUser" :tweet="$tweet" :view="true"></x-tweet>
@empty
    <div class="flex m-4 justify-center no-tweets">
        <span class="ml-2">No tweets yet</span>
    </div>
@endforelse
