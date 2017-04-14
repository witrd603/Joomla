jQuery(document).ready(function ($) {
    var timer, lastValue, lastOnline, request;

    // Search box.
    $('#myfriends-search').keyup(function (event) {
        var elem = $(this);
        var value = elem.val().trim();
        var online = $('#myfriends-online').is(':checked') ? 1 : 0;
        var mode = elem.attr('rel');

        if (value == lastValue && online == lastOnline) {
            return false;
        }

        clearTimeout(timer);
        if (undefined != request) {
            request.abort();
        }

        elem.addClass('search-loading');

        timer = setTimeout(function () {
            request = $.get(LoveFactory.route('searchFriends'), {
                search: value,
                online: online,
                mode: mode
            }, function (response) {
                $('#myfriends-results').html(response);
                elem.removeClass('search-loading');

                $(".lovefactory-quick-message").loveFactoryQuickMessage();
                $.LoveFactoryButtonFriendship();
            });

            lastValue = value;
            lastOnline = online;
        }, 1000);

        return true;
    });

    // Show only online friends.
    $('#myfriends-online').change(function () {
        $('#myfriends-search').keyup();
    });
});
