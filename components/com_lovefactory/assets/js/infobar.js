jQuery(document).ready(function ($) {
    // Show tooltips for infobar buttons.
    $.fn.tipsy.autoInfobar = function () {
        return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 'sw' : 'nw';
    };
    $('.is-tipsy').tipsy({gravity: $.fn.tipsy.autoInfobar});

    // Close infobar button.
    $('#lovefactory-infobar-close').click(function (event) {
        event.preventDefault();

        var elem = $(this);
        var url = elem.attr('href');

        $.get(url);

        $('.lovefactory-infobar-wrapper').slideToggle('slow');
    });

    setInterval(function () {
        $.get(LoveFactory.get('routeInfobarUpdate'), function (response) {
            // Update interactions.
            if (response.interactions) {
                $('.lovefactory-infobar-interactions span').html(response.interactions);
            }

            // Update messages.
            if (response.messages) {
                $('.lovefactory-infobar-messages span').html(response.messages);
            }

            // Update requests.
            if (response.requests) {
                $('.lovefactory-infobar-requests span').html(response.requests);
            }

            // Update comments.
            if (response.comments) {
                $('.lovefactory-infobar-comments span').html(response.comments);
            }
        }, 'json');
    }, $('.lovefactory-infobar-wrapper').attr('rel') * 1000);
});
