(function ($) {
    var timer;

    $.fn.extend({
        factoryTooltip: function (options) {
            var defaults = {
                message: 'Default tooltip message',
                timeout: 5,
                gravity: 'sw',
                error: false
            };

            if (typeof options == 'string') {
                options = {message: options}
            }

            options = $.extend(defaults, options);

            if (options.error) {
                options.cssClass = 'tip-error';
            }

            var elem = $(this);

            // Show current tip
            elem
                .attr('rel', 'tipsy')
                .attr('title', options.message)
                .tipsy({trigger: 'manual', 'gravity': options.gravity, html: true, cssClass: options.cssClass})
                .tipsy('show');

            // Set timeout to remove current tip
            clearTimeout(timer);
            timer = setTimeout(function () {
                $('.tipsy').fadeOut('fast', function () {
                    $(this).remove();
                });
            }, options.timeout * 1000);
        }
    });

    // Remove all tips on html clicks
    $('html').click(function (event) {
        $('.tipsy').fadeOut('fast', function () {
            $(this).remove();
        });
    });
})(jQuery);
