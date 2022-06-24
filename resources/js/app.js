import './bootstrap';
import 'jquery/src/jquery';

$(function () {
    function makeUrlsClickable() {
        $('.tweet-text').each(function () {
            let text = $(this).html();
            let regex = /(\b(https?|ftp|file):\/\/[\-A-Z0-9+&@#\/%?=~_|!:,.;]*[\-A-Z09+&@#\/%=~_| ])/img
            let replaced_text = text.replace(regex, "<a href='$1' class='text-blue-500' target='_blank'>$1</a>");
            $(this).html(replaced_text);
        });
    }

    makeUrlsClickable();

    $(".show-more").click(function () {
        let tweets = $("#tweets-list");
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
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "id": tweetId
            }, function () {
                $(document).find('.tweet-' + tweetId).remove();
                if ($(document).find('#tweets-list').children('div').length === 0) {
                    $(document).find('#tweets-list').append('<div class="flex m-4 justify-center"><span class="ml-2">No tweets yet</span></div>')
                }
            })
        }
    })

    $(".follow-user-form").submit(function (e) {
        e.preventDefault();
        let followingId = $(this).find('button').attr('data-following-id');
        $.post($(this).attr('action'), {
            "_token": $('meta[name="csrf-token"]').attr('content'),
        }, function (response) {
            if (response.status === 'success') {
                let followButton = $(".follow-user-form").find(`[data-following-id='${followingId}']`);
                if (followButton.attr('data-follow') == 1) {
                    followButton.html('unfollow');
                    followButton.attr('data-follow', 0);
                } else {
                    followButton.html('follow');
                    followButton.attr('data-follow', 1);
                }
            }
        })
    });

    $(document).on('submit', '.like-form', function (e) {
        e.preventDefault();
        let form = $(this);
        let isLiked = $(this).find('button').attr('data-is-liked');
        $.post($(this).attr('action'), {
            "_token": $('meta[name="csrf-token"]').attr('content'),
        }, function (response) {
            if (response.status === 'success') {
                switch (response.action) {
                    case 'liked':
                        form.find('.like-icon').removeClass('fa-regular')
                        form.find('.like-icon').addClass('fa-solid');
                        form.find('.likes-count').html(+form.find('.likes-count').html() + 1);
                        break;
                    case 'unliked':
                        form.find('.like-icon').removeClass('fa-solid')
                        form.find('.like-icon').addClass('fa-regular')
                        form.find('.likes-count').html(+form.find('.likes-count').html() - 1);
                        break;
                }
            }
        })
    })
})
