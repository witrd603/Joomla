(function ($) {
    // Swf Upload bind all
    $.fn.bindAll = function (options) {
        var $this = this;
        jQueryFactory.each(options, function (key, val) {
            $this.bind(key, val);
        });
        return this;
    };
})(jQueryFactory);

var request = null;

// Document ready
jQueryFactory(document).ready(function ($) {
    // Sortable lists
    $('.sortable').sortable({
        placeholder: 'ui-state-highlight',
        connectWith: '.sortable',
        tolerance: 'pointer',
        start: function (event, ui) {
            ui.item.parents('.lf-list').find('.lf-media-actions').hide();
            ui.item.parents('.lf-list').find('.lf-video-actions').hide();
        },
        stop: function (event, ui) {
            ui.item.parents('.lf-list').find('.lf-media-actions').show();
            ui.item.parents('.lf-list').find('.lf-video-actions').show();

            $('.lf-media-actions').css('visibility', 'hidden');

            if (null != request) {
                request.abort();
            }

            var serialize = '';
            $('.sortable').each(function () {
                var id = $(this).parents('li.lf-media-container:first').attr('id').replace('lf-media-', '');

                var temp = $(this).sortable('serialize', {key: id + '[]'});
                if ('' != temp) {
                    serialize += ('' == serialize ? '' : '&') + temp;
                }
            });

            request = $.post(
                route_save_order,
                {serialize: serialize, type: 'video'},
                function (response) {
                }, 'json');
        }
    }).disableSelection();

    // Listeners
    var listeners = {
        swfuploadLoaded: function (event) {
        },
        fileQueued: function (event, file) {
        },
        fileQueueError: function (file, errorCode, message) {
            alert(Joomla.JText._('COM_LOVEFACTORY_SWF_PHOTO_UPLOAD_ERROR_' + message));
        },
        fileDialogStart: function (event) {
        },
        fileDialogComplete: function (event, numFilesSelected, numFilesQueued) {
            if (numFilesSelected != 1) {
                alert(txt_select_just_one_image);
                return false;
            }

            var progress = $(this).parents('.lf-media-container').find('.lf-progressbar');
            progress.show();

            $(this).swfupload('startUpload');
        },
        uploadStart: function (event, file) {
            var progress = $(this).parents('.lf-media-container').find('.lf-progressbar');
            progress.progressbar('option', 'value', 0).show();
        },
        uploadProgress: function (event, file, bytesLoaded) {
            var progress = $(this).parents('.lf-media-container').find('.lf-progressbar');
            progress.progressbar('option', 'value', Math.floor(bytesLoaded * 100 / file.size));
        },
        uploadSuccess: function (event, file, serverData) {
            var progress = $(this).parents('.lf-media-container').find('.lf-progressbar');
            progress.hide();

            try {
                var response = eval('(' + serverData + ')');
            }
            catch (e) {
                alert(txt_error_uploading);
                return false;
            }

            $(this).parents('.lf-media-container')
                .find('input.lf-upload-thumb')
                .val(response.filename)
                .end()
                .find('.lovefactory-thumbnail')
                .css('background-image', 'url("' + response.source + '")')
                .end();
        },
        uploadComplete: function (event, file) {
            var progress = $(this).parents('.lf-media-container').find('.lf-progressbar');
            progress.progressbar('option', 'value', 0);

            $(this).swfupload('startUpload');
        },
        uploadError: function (event, file, errorCode, message) {
        }
    };

    // Add new video button
    $('.lovefactory-film-add').click(function (event) {
        event.preventDefault();

        var fieldset = $(this).parents('li.lf-media-container:first').find('fieldset');

        $('input, textarea', fieldset).val('').removeClass('error');
        $('.error-message', fieldset).hide();
        $('.lovefactory-thumbnail', fieldset).css('background-image', '');

        fieldset.slideToggle();

        if ('block' == fieldset.css('display')) {
            // Upload buttons
            $('.lf-upload', fieldset).each(function () {
                var type = $(this).parents('.lf-media-container').attr('id').replace('lf-media-', '');

                $(this).swfupload({
                    upload_url: route_upload,
                    file_size_limit: photos_max_size,
                    file_types: '*.jpg;*.gif;*.png;',
                    file_types_description: 'Images',
                    file_upload_limit: 0,
                    file_queue_limit: 1,
                    file_post_name: 'photo',
                    flash_url: root + 'components/com_lovefactory/assets/swfs/swfupload.swf',
                    button_image_url: root + 'components/com_lovefactory/assets/images/buttons/picture_add_swf.png',
                    button_width: 100,
                    button_height: 25,
                    button_text: '<span class="theFont">' + txt_upload + '</span>',
                    //button_text_style:      '.theFont { text-align: left; margin-left: 20px; font-size: 12px; font-family: Helvetica,Arial,sans-serif; font-weight: bold; }',
                    button_text_style: ".theFont { text-align: left; margin-left: 20px; font-size: 12px; font-family: Helvetica,Arial,sans-serif; font-weight: bold; }",
                    button_placeholder: $('.lf-button', this)[0],
                    button_window_mode: 'transparent',
                    button_cursor: SWFUpload.CURSOR.HAND,
                    debug: swfupload_debug,
                    post_params: {'PHPSESSID': session_id, session_name: session_id, type: type}
                }).bindAll(listeners);
            });

            // Progress bar
            $('.lf-progressbar', fieldset).progressbar({value: 0});
        }
    });

    // Save new video button
    $(document).on('click', 'li.lf-media-container fieldset a.lovefactory-bullet-add', function (event) {
        event.preventDefault();

        if ($(this).hasClass('lovefactory-loader-button')) {
            return false;
        }

        var link = $(this);
        var fieldset = link.parents('fieldset:first');
        var title = $('input[type="text"]:first', fieldset);
        var thumbnail = $('input[type="hidden"]:first', fieldset);
        var description = $('textarea:first', fieldset);
        var embed = $('textarea:eq(1)', fieldset);
        var type = link.parents('.lf-media-container').attr('id').replace('lf-media-', '');
        var errors = false;

        $('.error', fieldset).removeClass('error');

        if ('' == title.val()) {
            errors = true;
            title.addClass('error');
        }

        if ('' == embed.val()) {
            errors = true;
            embed.addClass('error');
        }

        // Check for errors
        if (errors) {
            $('.error-message', fieldset).html('Marked fields are required!').show();
            return false;
        }

        $('.error-message', fieldset).hide();

        var url = route_add_video;
        var data = {
            title: title.val(),
            thumbnail: thumbnail.val(),
            description: description.val(),
            code: embed.val(),
            type: type
        };
        var close = link.parents('li.lf-media-container').find('.lovefactory-film-add');

        link.addClass('lovefactory-loader-button');

        $.post(url, data, function (response) {
            link.removeClass('lovefactory-loader-button');

            if (1 == response.status) {
                close.click();

                link.parents('li.lf-media-container').find('.sortable')
                    .find('li:last').replaceWith(response.list_item).fadeIn().end()
                    .append('<li></li>');
            }
            else {
                if (response.redirect) {
                    window.location.href = route_redirect;
                }
                else {
                    $('.error-message', fieldset).html(response.error).show();
                }
            }

        }, 'json');
    });

    // Cancel new video button
    $(document).on('click', 'li.lf-media-container fieldset a.lovefactory-bullet-delete', function (event) {
        event.preventDefault();

        $(this).parents('li.lf-media-container').find('.lovefactory-film-add').click();
    });
});
