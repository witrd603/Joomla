jQuery(document).ready(function ($) {
    // Ratings mouse over.
    $(document).on('mouseover', '.lovefactory-rating-star', function () {
        var elem = $(this);

        elem.find('i').removeClass('icon-star-empty').addClass('icon-star');
        $('.lovefactory-rating-star:lt(' + elem.index() + ')').find('i').removeClass('icon-star-empty').addClass('icon-star');
    });

    // Ratings mouse out.
    $(document).on('mouseout', '.lovefactory-rating-star', function () {
        var elem = $(this);

        elem.find('i').removeClass('icon-star').addClass('icon-star-empty');
        $('.lovefactory-rating-star:lt(' + elem.index() + ')').find('i').removeClass('icon-star').addClass('icon-star-empty');
    });

    // Ratings click.
    $(document).on('click', '.lovefactory-rating-star', function (event) {
        event.preventDefault();

        var elem = $(this),
            loader = $('.stars-loader'),
            stars = $('.rating-stars'),
            fieldset = $('.well.ratings'),
            user_id = elem.data('user-id'),
            url = elem.attr('href');

        loader.show();
        stars.hide();

        $.post(url, function (response) {
            if (!response.status) {
                loader.hide();
                stars.show().factoryTooltip({message: response.message + ' ' + response.error, cssClass: 'tip-error'});
            }
            else {
                var message = response.message;
                $.get(LoveFactory.route('ratingUpdate'), {user_id: user_id}, function (response) {
                    loader.hide();
                    fieldset.replaceWith(response);
                    $('.my-rating').factoryTooltip({message: message, gravity: 's'});
                });
            }
        }, 'json');
    });

    // Update user status.
    $('.update-status').click(function (event) {
        event.preventDefault();

        var loader = $('.user_status_loader');
        var text = $('#user-status-update').val();

        loader.show();

        $.post(LoveFactory.get('routeUpdateStatus'), {text: text}, function (response) {
            loader.hide();
            if (response.status) {
                $('#user-status-update').val(response.update).factoryTooltip({message: response.message});
            } else {
                $('#user-status-update').factoryTooltip({
                    message: response.message + ' ' + response.error,
                    error: true
                });
            }

        }, 'json');
    });

    $('textarea').autosize();
});
