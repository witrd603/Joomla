jQuery(document).ready(function ($) {
    $('.lovefactory-shoutbox-messages').each(function (index, element) {
        var timer,
            request,
            lastUpdate = '',
            $this = $(this),
            refreshInterval = $this.attr('rel');

        $this.bind('update', function (event, reset) {
            // Perform update.
            if (reset && request) {
                request.abort();
            }

            if ('' == lastUpdate) {
                lastUpdate = $this.parents('.lovefactory-module:first').attr('rel');
            }

            var url = 'index.php?option=com_lovefactory&controller=module&task=shoutboxgetmessages&lastupdate=' + lastUpdate;

            request = $.get(url, function (response) {
                lastUpdate = response.lastUpdate;

                if (response.messages) {
                    for (var i = 0, length = response.messages.length; i < length; i++) {
                        var message = response.messages[i];
                        var count = $this.find('.lovefactory-shoutbox-message').length;
                        var alternate = count % 2 ? 'alternate' : '';

                        $this.prepend('<div class="lovefactory-shoutbox-message ' + alternate + '">' + message.html + '</div>');
                    }
                }
            }, 'json');

            if (reset) {
                clearInterval(timer);

                timer = setInterval(function () {
                    $this.trigger('update');
                }, refreshInterval * 1000);
            }
        });

        timer = setInterval(function () {
            $this.trigger('update');
        }, refreshInterval * 1000);
    });

    $('.lovefactory-shoutbox-post form').submit(function (event) {
        event.preventDefault();

        var elem = $(this);

        $.ajax({
            url: elem.attr('action'),
            data: elem.serialize(),
            type: 'POST'
        }).done(function () {
            elem.parent().prev().trigger('update', true);
        });

        var input = $(this).find('input[type="text"]');
        input.val('');
    });
});
