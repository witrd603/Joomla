jQuery(document).ready(function ($) {
    // Privacy buttons.
    $('.videos .privacy-button').privacyButton({
        onSelect: function (value, ui) {
            var id = ui.parents('.video:first').attr('id').replace('video-', ''),
                elem = ui.parents('.privacy-button:first').find('.privacy-current'),
                data = {'batch[]': id, privacy: value, type: 'video'};

            elem.addClass('icon-loader');

            $('.privacy-status').hide();

            $.post(LoveFactory.route('video.setPrivacy'), data, function (response) {
                elem.removeClass('icon-loader');

                if (!response.status) {
                    ui.trigger('reset');
                    $('.privacy-status').show().html(response.message);
                }
            }, 'json');
        }
    });

    // Sortable videos.
    $('.videos').sortable({
        handle: '.move-handle',
        stop: function (event, ui) {
            var data = $('.videos').sortable('serialize');

            $.ajax({
                url: LoveFactory.route('videos.saveOrder'),
                type: 'POST',
                data: data
            });
        }
    });

    // Batch privacy button.
    $('.actions .privacy-button').privacyButton({
        onSelect: function (value, ui) {
            var data = $('#adminForm input[type="checkbox"]').serializeArray();

            data.push({name: 'type', value: 'video'});
            data.push({name: 'privacy', value: value});

            if ('' == data) {
                return false;
            }

            var new_icon = ui.find('i').attr('class').replace('factory-icon ', '');
            var elem = ui.parents('.privacy-button:first').find('.privacy-current');

            elem.addClass('icon-loader');
            $('.privacy-status').hide();

            $.ajax({
                url: LoveFactory.route('video.setPrivacy'),
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                    $('input[name="batch[]"]:checked').each(function (index, element) {
                        elem.removeClass('icon-loader');

                        $('input[name="batch[]"]:checked').each(function (index, element) {
                            var elem = $(element),
                                icon = elem.parents('.video:first').find('.privacy-current')
                            parent = elem.closest('.video'),
                                photoId = parent.attr('id').replace('video-', '');

                            if (-1 !== $.inArray(photoId, response.updated)) {
                                icon.removeClass('icon-globe icon-users icon-lock').addClass(new_icon);
                            }
                        });

                        if (!response.status) {
                            $('.privacy-status').show().html(response.message);
                        }
                    });
                }
            });
        }
    });

    // Filter privacy change.
    $('#filter_privacy').change(function (event) {
        $('#adminForm').submit();
    });

    // Remove empty fields.
    $('#adminForm').submit(function (event) {
        $(this).find(':input[value=""]').attr('name', '');

        return true;
    });

    // Button delete.
    $('.batch-delete').click(function (event) {
        event.preventDefault();

        var data = $('#adminForm input[type="checkbox"]').serialize();

        if ('' == data) {
            return false;
        }

        $.ajax({
            url: LoveFactory.route('videos.delete'),
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.removed.length) {
                    $.each(response.removed, function (index, element) {
                        $('#video-' + element).fadeOut(function () {
                            $(this).remove();

                            if (!$('.video').length) {
                                $('.check-all-container, .actions').hide();
                            }
                        });
                    });
                }
            }
        });
    });

    // Button Add videos.
    $('.video-add').click(function (event) {
        event.preventDefault();

        var elem = $(this);
        var url = elem.attr('href');

        $.LoveFactoryDialog(url, {
            elem: elem,
            minWidth: 700,
            onSubmit: function (buton, dialog) {
                var form = dialog.find('form:first');
                form.submit();

                // TODO FACTORY: Implement if somehow there is time...
//        if ('undefined' == typeof FormData) {
//          form.submit();
//        } else {
//          var xmlHttpRequest = new XMLHttpRequest();
//          xmlHttpRequest.open('POST', form.attr('action'), true);
//
//          var formData = new FormData(form[0]);
//
//          // Progress bar
//          xmlHttpRequest.upload.onprogress = function(e) {
//            if (e.lengthComputable) {
//              var percentComplete = (e.loaded / e.total) * 100;
//              console.log(percentComplete);
//              $('#factory-progress').show().css('width', percentComplete + '%');
//            }
//          };
//
//          xmlHttpRequest.upload.onloadstart = function (e) {
//            console.log('start');
//          }
//
//          // On success event
//          xmlHttpRequest.onreadystatechange = function(e) {
//            if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
//              //var response = $.parseJSON(xmlHttpRequest.responseText);
//
//              console.log('success');
//            }
//          };
//
//          // Send Ajax form
//          xmlHttpRequest.setRequestHeader('X_REQUESTED_WITH', 'XMLHttpRequest');
//          xmlHttpRequest.send(formData);
//        }
            }
        });
    });

    // Retrieve Youtube data.
    $(document).on('click', '.retrieve-youtube-data', function (event) {
        event.preventDefault();

        var elem = $(this);
        var code = $('#video_code').attr('value');
        var url = LoveFactory.route('video.retrieveYoutubeData');

        elem.prev().show();

        $('#video_title, #video_description, #video_thumbnail_external').val('');
        $('#video_thumbnail_external').attr('disabled', 'disabled');
        $('.video-youtube-thumbnail').hide();

        $.get(url, {code: code}, function (response) {
            elem.prev().hide();
            if (response.status) {
                $('#video_title').val(response.title);
                $('#video_description').val(response.description);
                $('.video-youtube-thumbnail').show().find('img').attr('src', response.thumbnail);
                $('#video_thumbnail_external').attr('disabled', false).val(response.thumbnail);
                $('#video_thumbnail').replaceWith('<input id="video_thumbnail" type="file" name="video[thumbnail]">');
            } else {
                elem.factoryTooltip({error: true, message: response.error});
            }
        }, 'json');
    });

    // Thumbnail field change event.
    $(document).on('change', '#video_thumbnail', function (event) {
        $('.video-youtube-thumbnail').hide();
        $('#video_thumbnail_external').attr('disabled', true).val('');
    });
});
