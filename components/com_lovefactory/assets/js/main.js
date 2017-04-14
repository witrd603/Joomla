var LoveFactory = {
    options: {},

    set: function (option, value) {
        if (typeof option == 'object') {
            for (var variable in option) {
                LoveFactory.set(variable, option[variable]);
            }
        } else {
            this.options[option] = value;
        }
    },

    get: function (option, defaults) {
        defaults = typeof defaults !== 'undefined' ? defaults : null;

        if (typeof this.options[option] === 'undefined') {
            return defaults;
        }

        return this.options[option];
    },

    route: function (option, defaults) {
        option = 'route' + option;

        return LoveFactory.get(option, defaults);
    }
}

jQuery(document).ready(function ($) {
    // Remove empty fields from a form when submitting it.
    $('form.lovefactory-form').submit(function (event) {

        $('input, select', $(this)).each(function (index, element) {
            var $element = $(element);

            if ('' == $element.val()) {
                $element.attr('name', '');
            }
        });

        return true;
    });

    // Submit form when filters change.
    $('select', '.filters', 'form.lovefactory-form').change(function (event) {
        $(this).parents('form:first').submit();
    });

    // Select all checkboxes from a list.
    $('input[type="checkbox"].batch', 'form.lovefactory-form').click(function (event) {
        var elem = $(this);
        var checked = elem.is(':checked');

        $('input[name="batch[]"]').attr('checked', checked);
    });

    /* Item comments */

    // Submit comment button.
    $('.item-comments .buttons button[type="button"]').click(function (event) {
        event.preventDefault();

        var elem = $(this),
            loader = elem.find('.fa-spin'),
            url = elem.data('url'),
            message = elem.parents('.item-comments:first').find('textarea:first'),
            asset = elem.attr('id').split('-'),
            data = {
                'item_type': asset[0],
                'item_id': asset[1],
                'message': message.val()
            };

        elem.attr('disabled', 'disabled');
        loader.show();

        $.post(url, {data: data}, function (response) {
            elem.attr('disabled', false);
            loader.hide();

            if (!response.status) {
                if (response.status || !response.redirect) {
                    loader.hide();
                }

                if (response.redirect) {
                    window.location.href = response.redirect;
                }
                else {
                    elem.factoryTooltip({message: response.message + ' ' + response.error, error: true});
                }
            }
            else {
                var msg = response.message,
                    url = elem.parents('.item-comments:first').find('div.comments-wrapper').data('url');

                $.get(url, {item_id: asset[1], item_type: asset[0]}, function (response) {
                    var $response = $(response);

                    loader.hide();
                    message.val('');

                    elem.parents('.item-comments:first').find('div.comments-wrapper').replaceWith($response);
                    $response.find('li:first i.factory-icon').factoryTooltip({message: msg});
                });
            }
        }, 'json');
    });

    // Comment delete button.
    $(document).on('click', '.item-comments .comment-delete', function (event) {
        event.preventDefault();

        var elem = $(this),
            url = elem.attr('href'),
            icon = elem.find('span.fa');

        icon.removeClass('fa-times').addClass('fa-refresh fa-spin');

        $.post(url, function (response) {
            icon.addClass('fa-times').removeClass('fa-refresh fa-spin');

            if (response.status) {
                elem.parents('li:first').fadeOut('fast', function () {
                    $(this).html(response.message).fadeIn();
                });
            }
            else {
                elem.factoryTooltip({message: response.message + ' ' + response.error, error: true, gravity: 'se'});
            }
        }, 'json');

        return true;
    });
});
