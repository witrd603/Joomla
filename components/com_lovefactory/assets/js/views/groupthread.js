jQuery(document).ready(function ($) {
    // Comment delete button.
    $(document).on('click', '.view-groupthread .comment-delete', function (event) {
        event.preventDefault();

        var elem = $(this);
        var id = elem.parents('li.comment:first').attr('id').replace('comment-', '');
        var url = LoveFactory.get('routeDeleteComment');

        elem.prepend('<i class="factory-icon icon-loader"></i>');

        $.post(url, {id: id}, function (response) {
            elem.find('i:first').remove();

            if (response.status) {
                elem.parents('li:first').fadeOut('fast', function () {
                    $(this).html(response.message).fadeIn();
                });
            } else {
                elem.factoryTooltip({message: response.message + ' ' + response.error, error: true, gravity: 'se'});
            }
        }, 'json');

        return true;
    });

    // Comment ban user.
    $(document).on('click', '.view-groupthread .user-comment-ban', function (event) {
        event.preventDefault();

        var elem = $(this);
        var url = elem.attr('href');

        elem.prepend('<i class="factory-icon icon-loader"></i>');

        $.post(url, function (response) {
            elem.find('i:first').remove();

            if (response.status) {
                elem.factoryTooltip({message: response.message, gravity: 'se'});
                elem.replaceWith(response.text);
            } else {
                elem.factoryTooltip({message: response.message + ' ' + response.error, error: true, gravity: 'se'});
            }
        }, 'json');

        return true;
    });
});
