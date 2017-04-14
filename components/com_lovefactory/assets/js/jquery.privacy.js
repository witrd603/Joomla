(function ($) {
    $.fn.privacyButton = function (options) {
        if (true === this.data('privacy-button')) {
            return;
        }

        this.data('privacy-button', true);

        options = $.extend({}, $.fn.privacyButton.defaults, options);

        $(document).on('click', '.privacy-toggle', function (event) {
            event.preventDefault();
            event.stopImmediatePropagation();

            var elem = $(this);
            var opened = elem.hasClass('opened');

            $.fn.privacyButton.closeOthers();

            if (!opened) {
                elem.next().show();
            } else {
                elem.next().hide();
            }

            elem.toggleClass('opened');
        });

        $(document).on('click', '.privacy-options a', function (event) {
            event.preventDefault();

            var elem = $(this);
            var parent = elem.parents('.privacy-button:first').find('.privacy-toggle i:first');
            var className = elem.find('i:first').attr('class').replace('factory-icon ', '');
            var value = elem.attr('class').replace('privacy-', '');
            var original = parent.attr('class').match(/icon-(globe|lock|users)/g)[0];

            elem.bind('reset', function () {
                parent.removeClass('icon-globe icon-users icon-lock').addClass(original);
            });

            parent.removeClass('icon-globe icon-users icon-lock').addClass(className);

            // Trigger onSelect event.
            options.onSelect(value, elem);

            // Update hidden input if present.
            var hiddenInput = elem.parents('.privacy-button:first').find('input[type="hidden"]');
            if (hiddenInput.length) {
                hiddenInput.val(value);
            }
        });
    }

    $.fn.privacyButton.defaults = {
        onSelect: function (value, ui) {
        }
    };

    $.fn.privacyButton.closeOthers = function () {
        $('.privacy-options').hide();
        $('.opened').removeClass('opened');
    }

    $('html').click(function (event) {
        $.fn.privacyButton.closeOthers();
    });
})(jQuery);
