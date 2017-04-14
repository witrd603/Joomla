(function ($) {
    $.fn.autogrow = function (options) {

        this.filter('textarea').each(function () {

            var $this = $(this),
                minHeight = $this.height(),
                lineHeight = $this.css('lineHeight');
            lineHeight = Math.floor(parseInt($(this).css('font-size').replace('px', '')) * 1.25);

            if ($this.hasClass('autogrow')) {
                return false;
            }

            $this.addClass('autogrow').css('resize', 'none');

            var shadow = $('<div></div>').css({
                position: 'absolute',
                top: -10000,
                left: -10000,
                'word-wrap': 'break-word',
                'text-align': $(this).css('text-align'),
                'padding': $(this).css('padding'),
                'border': $(this).css('border'),
                width: $(this).width(),
                fontSize: $this.css('fontSize'),
                fontFamily: $this.css('fontFamily'),
                lineHeight: $this.css('lineHeight'),
                resize: 'none'
            }).appendTo(document.body);

            var update = function () {

                var times = function (string, number) {
                    for (var i = 0, r = ''; i < number; i++) r += string;
                    return r;
                };

                var val = this.value
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/&/g, '&amp;')
                    .replace(/\n$/, '<br/>&nbsp;')
                    .replace(/\n/g, '<br/>')
                    .replace(/ {2,}/g, function (space) {
                        return times('&nbsp;', space.length - 1) + ' '
                    });

                shadow.html(val);
                $(this).css('height', Math.max(shadow.height(), lineHeight));
            }

            $(this).change(update).keyup(update).keydown(update);

            update.apply(this);

        });

        return this;
    }
})(jQuery);
