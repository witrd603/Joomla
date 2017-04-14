(function ($) {
    $.fn.extend({
        loveFactoryQuickMessage: function () {
            return this.each(function () {
                $(this).click(function (event) {
                    event.preventDefault();

                    var elem = $(this);
                    var url = elem.attr('href');

                    $.LoveFactoryDialog(url, {elem: elem});
                });
            });
        },

        LoveFactoryCheckAll: function () {
            $(this).change(function () {
                var elem = $(this);
                var checked = elem.is(':checked');

                $('input[type="checkbox"][name="batch[]"]').attr('checked', checked);
            });
        },

        LoveFactoryFieldUsernameAjaxCheck: function () {
            return this.each(function () {
                $(this).change(function (event) {
                    var elem = $(this);
                    var value = elem.val();
                    var url = 'index.php?option=com_lovefactory&controller=signup&task=checkusername&format=raw';

                    elem.toggleClass('lovefactory-field-loading');
                    elem.next('div.error-username-exists').hide();

                    $.get(url, {value: value}, function (response) {
                        elem.toggleClass('lovefactory-field-loading');

                        if (response.status) {
                            elem.removeClass('lovefactory-field-error').addClass('lovefactory-field-valid');
                        }
                        else {
                            elem.addClass('lovefactory-field-error').removeClass('lovefactory-field-valid');
                            elem.next('div.error-username-exists').show();
                        }
                    }, 'json');
                });
            });
        },

        LoveFactoryFieldEmailAjaxCheck: function () {
            return this.each(function () {
                $(this).change(function (event) {
                    var elem = $(this);
                    var value = elem.val();
                    var url = 'index.php?option=com_lovefactory&controller=signup&task=checkemail&format=raw';

                    elem.toggleClass('lovefactory-field-loading');
                    elem.next('div.error-email-exists').hide();

                    $.get(url, {value: value}, function (response) {
                        elem.toggleClass('lovefactory-field-loading');

                        if (response.status) {
                            elem.removeClass('lovefactory-field-error').addClass('lovefactory-field-valid');
                        }
                        else {
                            elem.addClass('lovefactory-field-error').removeClass('lovefactory-field-valid');
                            elem.next('div.error-email-exists').show();
                        }
                    }, 'json');
                });
            });
        }
    });

    $.extend({
        LoveFactoryDialog: function (url, options) {
            var defaults = {
                modal: true,
                resizable: false,
                draggable: false,
                minHeight: 10,
                minWidth: 500,
                elem: null,
                open: function (event, ui) {
                    var dialog = $(event.target);

                    dialog
                        .find('.dialog-button-close')
                        .click(function (event) {
                            event.preventDefault();
                            dialog.dialog('close');
                        })
                        .end()

                        .find('.dialog-button-submit')
                        .click(function (event) {
                            event.preventDefault();
                            var button = $(this);
                            opts.onSubmit(button, dialog);
                        })
                        .end()

                    opts.onOpen(dialog);

                    if (null != opts.elem) {
                        opts.elem.find('i').toggleClass('icon-loader');
                    }
                },
                close: function (event, ui) {
                    $(this).dialog('destroy');
                    $(event.target).remove();
                    opts.elem.find('span.fa').addClass('fa-warning').removeClass('fa-refresh fa-spin');
                },
                onSubmitSuccess: function (data, elem) {
                },
                onOpen: function (elem) {
                },
                onSubmit: function (button, dialog) {
                    var form = dialog.find('form');
                    var url = form.attr('action');
                    var method = form.attr('method');
                    var icon = button.find('i');

                    icon.toggleClass('icon-loader');

                    $.ajax({
                        url: url,
                        type: method,
                        data: form.serialize(),
                        dataType: 'json',
                        complete: function (jqXHR, textStatus) {
                            opts.elem.find('span.fa').addClass('fa-warning').removeClass('fa-refresh fa-spin');
                            icon.toggleClass('icon-loader');
                        },
                        success: function (data, textStatus, jqXHR) {
                            icon.toggleClass('icon-loader');

                            if (data.status || !data.redirect) {
                                icon.toggleClass('icon-loader');
                            }

                            if (!data.status) {
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                } else {
                                    button.factoryTooltip({message: data.message + ' ' + data.error, error: true});
                                }
                            } else {
                                dialog.dialog('close');
                                opts.elem.factoryTooltip({message: data.message});
                                opts.onSubmitSuccess(data, opts.elem);
                            }
                        }
                    });
                }
            }
            var opts = $.extend(defaults, options);

            if (null != opts.elem) {
                if (opts.elem.find('span.fa')) {
                    opts.elem.find('span.fa').removeClass('fa-warning').addClass('fa-refresh fa-spin');
                }
                else if (!opts.elem.find('i').length) {
                    opts.elem.prepend('<i class="factory-icon"></i>');
                }

                opts.elem.find('i').toggleClass('icon-loader');
            }

            $.get(url, function (response) {
                if (typeof response == 'object') {
                    if (!response.status) {
                        opts.elem.factoryTooltip({message: response.message + ' ' + response.error, error: true});
                    }

                    if (opts.elem.find('span.fa')) {
                        opts.elem.find('span.fa').addClass('fa-warning').removeClass('fa-refresh fa-spin');
                    }
                    else {
                        opts.elem.find('i').toggleClass('icon-loader');
                    }

                    return false;
                }

                var dialog = $('<div>' + response + '</div>').find('#lovefactory-dialog').dialog(opts);

                dialog.parent().wrap('<div class="lovefactory-object" />');

                return true;
            });
        },

        LoveFactoryAjaxAction: function (selector) {
            $(document).on('click', selector, function (event) {
                event.preventDefault();

                var elem = $(this);
                var url = elem.attr('href');
                var icon = elem.find('i');
                var image = icon.css('background-image');
                var data = elem.attr('data-confirm');

                if ('undefined' !== typeof data && !confirm(data)) {
                    return false;
                }

                icon.toggleClass('icon-loader').css('background-image', '');

                $.get(url, function (response) {
                    if (response.status || !response.redirect) {
                        icon.toggleClass('icon-loader').css('background-image', image);
                    }

                    if (!response.status) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            elem.factoryTooltip({message: response.message + ' ' + response.error, error: true});
                        }
                    } else {
                        elem.factoryTooltip({message: response.message});

                        if (undefined != response.html) {
                            elem.replaceWith(response.html);
                        }
                    }
                });
            });
        },

        LoveFactoryButtonFriendship: function () {
            $(document).on('click', '.button-friendship[data-status="0"]', function (event) {
                event.preventDefault();

                var elem = $(this);
                var url = elem.attr('href');

                $.LoveFactoryDialog(url, {
                    elem: elem,
                    onSubmitSuccess: function (data) {
                        elem.replaceWith(data.html);
                    }
                });
            });
        },

        LoveFactoryButtonRelationship: function () {
            $(document).on('click', '.button-relationship[data-status="0"]', function (event) {
                event.preventDefault();

                var elem = $(this);
                var url = elem.attr('href');

                $.LoveFactoryDialog(url, {
                    elem: elem,
                    onSubmitSuccess: function (data) {
                        elem.replaceWith(data.html);
                    }
                });
            });
        },

        LoveFactoryButtonReport: function () {
            $(document).on('click', '.lovefactory-button-report', function (event) {
                event.preventDefault();

                var elem = $(this);
                var url = elem.attr('href');

                $.LoveFactoryDialog(url, {
                    elem: elem,
                    onSubmitSuccess: function (data, elem) {
                        if (data.status) {
                            elem.replaceWith(data.text);
                        }
                    }
                });
            });
        }
    });
})(jQuery);
