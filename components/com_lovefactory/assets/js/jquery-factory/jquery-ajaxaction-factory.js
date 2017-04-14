(function ($) {
    $.fn.ajaxAction = function (settings) {

        // Default settings
        var defaultSettings = {
            loadingClass: 'lovefactory-loader-button',
            method: 'get',
            timeout: 3000
        }

        // Extend settings
        settings = $.extend(defaultSettings, settings);

        // Loop through the elements
        return this.each(function () {
            var elem = $(this);
            var tip = new Tip();
            var loading = false;
            var timer = null;

            elem.hasTip = false;

            // On click send the request
            elem.click(function (event) {
                event.preventDefault();

                if (!elem.hasClass('lovefactory-ajax-action')) {
                    window.document.location = elem.attr('href');
                    return false;
                }

                // Already waiting for a response
                if (loading) {
                    return false;
                }

                // Remve previous tip
                if (elem.hasTip) {
                    clearTimeout(timer);
                    tip.hide();
                }

                // Add loading class + color tip container
                elem.addClass(settings.loadingClass).addClass('colorTipContainer');

                // Send the request
                $.ajax({
                    // Settings
                    url: elem.attr("href"),
                    method: settings.method,
                    dataType: "json",

                    // Before send
                    beforeSend: function () {
                        loading = true;
                    },

                    // On complete
                    complete: function () {
                        // Remove loading class
                        elem.removeClass(settings.loadingClass)

                        loading = false;
                        tip.show();
                        elem.hasTip = true;

                        timer = setTimeout(function () {
                            tip.hide();
                        }, settings.timeout);
                    },

                    // On success
                    success: function (response, textStatus) {
                        var message = response.success ? response.message : response.message + (undefined != response.error ? '<br />' + response.error : '');

                        elem
                            .append(tip.generate(message))
                            .removeClass('black').removeClass('red')
                            .addClass(response.success ? 'black' : 'red');

                        // If action successfull
                        if (response.success) {
                            // Set new link
                            if (undefined != response.link) {
                                elem.attr('href', response.link);
                            }

                            // Set new text
                            if (undefined != response.text) {
                                elem.find('span:first').text(response.text);
                            }

                            // Remove old class
                            if (undefined != response.removeClass) {
                                var classes = response.removeClass.split(' ');

                                for (var i = 0, count = classes.length; i < count; i++) {
                                    elem.removeClass(classes[i]);
                                }
                            }

                            // Add new class
                            if (undefined != response.addClass) {
                                elem.addClass(response.addClass);
                            }

                            // Is ajax link anymore?
                            if (undefined != response.ajax && !response.ajax) {
                                elem.attr('rel', false);
                            }
                        }
                        else {
                            if (undefined != response.redirect) {
                                document.location.href = response.redirect;
                            }
                        }
                    },

                    // On error
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        elem
                            .append(tip.generate(textStatus))
                            .removeClass('black').removeClass('red')
                            .addClass('red');
                    }
                })
            })
        });
    }

    // Tip definition
    function Tip() {
    }

    Tip.prototype = {
        generate: function (message) {
            this.tip = $('<span class="colorTip">' + message + '<span class="pointyTipShadow"></span><span class="pointyTip"></span></span>');

            var tip = this;
            this.tip.click(function (event) {
                event.preventDefault();
                event.stopPropagation();

                tip.hide();
            });

            return this.tip;
        },

        show: function () {
            // Center the tip and start a fadeIn animation
            this.tip
                .css('margin-left', -this.tip.outerWidth() / 2)
                .css('top', (-this.tip.outerHeight() - 5))
                .fadeIn('fast');
        },

        hide: function () {
            this.tip.fadeOut('fast', function () {
                $(this).remove();
            });
        }
    }
})(jQueryFactory);
