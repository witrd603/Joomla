jQuery(document).ready(function ($) {
    // Activity delete button.
    $(document).on('click', '.view-activity .action-delete', function (event) {
        event.preventDefault();

        var elem = $(this);
        var url = elem.attr('href');

        elem.find('i.factory-icon').toggleClass('icon-loader');

        $.get(url, function (response) {
            if (response.status) {
                elem.parents('td:first').fadeOut('fast', function () {
                    $(this).html(response.message).fadeIn('fast');
                });
            }
            else {
                elem
                    .find('i.factory-icon')
                    .toggleClass('icon-loader')
                    .end()
                    .factoryTooltip({message: response.message + ' ' + response.error, error: true, gravity: 'se'});
            }
        }, 'json');

        return true;
    });
});
