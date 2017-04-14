jQuery(document).ready(function ($) {
    // Privacy buttons.
    $('.photos .privacy-button').privacyButton({
        onSelect: function (value, ui) {
            var id = ui.parents('.photo:first').attr('id').replace('photo-', ''),
                elem = ui.parents('.privacy-button:first').find('.privacy-current'),
                data = {'batch[]': id, privacy: value, type: 'photo'};

            elem.addClass('icon-loader');

            $('.privacy-status').hide();

            $.post(LoveFactory.route('setPrivacy'), data, function (response) {
                elem.removeClass('icon-loader');

                if (!response.status) {
                    ui.trigger('reset');
                    $('.privacy-status').show().html(response.message);
                }
            }, 'json');
        }
    });

    // Sortable photos.
    $('.photos').sortable({
        handle: '.move-handle',
        stop: function (event, ui) {
            var data = $('.photos').sortable('serialize');

            $.ajax({
                url: LoveFactory.route('photosSaveOrder'),
                type: 'POST',
                data: data
            });
        }
    });

    // Batch privacy button.
    $('.actions .privacy-button').privacyButton({
        onSelect: function (value, ui) {
            var data = $('#adminForm input[type="checkbox"]').serializeArray();

            data.push({name: 'type', value: 'photo'});
            data.push({name: 'privacy', value: value});

            if ('' == data) {
                return false;
            }

            var new_icon = ui.find('i').attr('class').replace('factory-icon ', '');
            var elem = ui.parents('.privacy-button:first').find('.privacy-current');

            elem.addClass('icon-loader');
            $('.privacy-status').hide();

            $.ajax({
                url: LoveFactory.route('setPrivacy'),
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response) {
                    elem.removeClass('icon-loader');

                    $('input[name="batch[]"]:checked').each(function (index, element) {
                        var elem = $(element),
                            icon = elem.parents('.photo:first').find('.privacy-current')
                        parent = elem.closest('.photo'),
                            photoId = parent.attr('id').replace('photo-', '');

                        if (-1 !== $.inArray(photoId, response.updated)) {
                            icon.removeClass('icon-globe icon-users icon-lock').addClass(new_icon);
                        }
                    });

                    if (!response.status) {
                        $('.privacy-status').show().html(response.message);
                    }
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
            url: LoveFactory.route('photoDelete'),
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.removed.length) {
                    $.each(response.removed, function (index, element) {
                        $('#photo-' + element).fadeOut(function () {
                            $(this).remove();

                            if ($('.photos .photo').length) {
                                $('.check-all-container, .actions').show();
                            } else {
                                $('.check-all-container, .actions').hide();
                            }
                        });
                    });
                }
            }
        });
    });

    // Button Add photos.
    $('.photos-upload').click(function (event) {
        event.preventDefault();

        var elem = $(this);
        var url = elem.attr('href');

        $.LoveFactoryDialog(url, {
            elem: elem,
            onOpen: function (dialog) {
                if ('undefined' == typeof FormData) {
                    dialog.find('.dialog-button-select').remove().end().find('#batch').css({
                        position: 'relative',
                        top: 0,
                        visibility: 'visible'
                    }).show();
                }
                else {
                    dialog.find('.lovefactory-dialog-buttons').find('select:first').show();
                }
            },
            onSubmit: function (buton, dialog) {

                if ('undefined' == typeof FormData) {
                    var form = dialog.find('form:first');
                    form.submit();

                    return true;
                }

                var formData = new FormData();

                for (var i = 0, count = bufferUpload.length; i < count; i++) {
                    var file = bufferUpload[i];

                    if ('undefined' == typeof file) {
                        continue;
                    }

                    var privacy = $('.lovefactory-dialog-friendship ul.files select:eq(' + i + ')').val();

                    formData.append('batch[]', file);
                    formData.append('privacy[]', privacy);
                }

                var timer;
                bufferUpload = [];
                $('.upload-status').html('').show();

                // Send Ajax form
                var xmlHttpRequest = new XMLHttpRequest();
                xmlHttpRequest.open('POST', LoveFactory.route('photoUpload'), true);
                xmlHttpRequest.setRequestHeader('X_REQUESTED_WITH', 'XMLHttpRequest');

                xmlHttpRequest.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        var percentComplete = (e.loaded / e.total) * 100;
                        $('#progressbar').progressbar({value: percentComplete});
                    }
                };

                xmlHttpRequest.upload.onloadstart = function (e) {
                    $('#progressbar').progressbar({value: 0});
                    $('ul.files').hide();
                }

                xmlHttpRequest.onreadystatechange = function (e) {
                    if (xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                        $('.photos').load(LoveFactory.route('photosUpdate'), function () {
                            if ($('.photos .photo').length) {
                                $('.check-all-container, .actions').show();
                            } else {
                                $('.check-all-container, .actions').hide();
                            }
                        });
                        dialog.dialog('close');

                        var response = jQuery.parseJSON(xmlHttpRequest.responseText);

                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            if (response.status) {
                                for (var i = 0, count = response.photos.length; i < count; i++) {
                                    var photo = response.photos[i];
                                    $('.upload-status').append('<li><i class="factory-icon icon-' + (!photo.status ? 'cross' : 'plus') + '-circle"></i><b>' + photo.name + '</b> - ' + photo.message + '</li>');

                                    clearTimeout(timer);
                                    timer = setTimeout(function () {
                                        $('.upload-status').fadeOut();
                                    }, 5000);
                                }
                            }
                            else {
                                $('.upload-status').html('').hide();
                                alert(response.message + "\n" + response.error);
                            }
                        }
                    }
                }

                xmlHttpRequest.send(formData);
            }
        });
    });

    // Set main photo.
    $('.photo-set-main').click(function (event) {
        event.preventDefault();

        var data = $('#adminForm input[type="checkbox"]').serialize();
        var url = LoveFactory.route('setProfilePhoto');
        var elem = $(this);

        if ('' == data) {
            return false;
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (!response.status) {
                    elem.factoryTooltip({message: response.message + ' ' + response.error, error: true});
                } else {
                    $('.profile-photo').hide();
                    $('#photo-' + response.photo_id + ' .move-handle').after('<i class="factory-icon icon-star profile-photo"></i>');
                    $('#photo-' + response.photo_id).factoryTooltip({message: response.message});
                }
            }
        });
    });

    var bufferUpload = [];

    $(document).on('click', '.dialog-button-select', function (event) {
        event.preventDefault();

        $('#batch').click();
    })

    $(document).on('change', '#batch', function (event) {
        var files = $('ul.files');

        if ('undefined' == typeof FormData) {
            var elem = $(this);

            files.append('<li><a href="#" class="remove-buffer-file"><i class="factory-icon icon-cross-circle"></i></a>&nbsp;' + elem.val() + '</li>');
            files.find('li:last').append(elem);

            var clone = elem.clone();
            elem.attr('id', '').css('display', 'none');
            elem.parents('form:first').append(clone);
        } else {
            for (var i = 0, count = this.files.length; i < count; i++) {
                var file = this.files[i];
                bufferUpload.push(file);

                files.append(
                    '<li>' +
                    '<a href="#" class="remove-buffer-file" rel="' + i + '">' +
                    '<i class="factory-icon icon-cross-circle"></i>' +
                    '</a>' +
                    '<span></span>' +
                    '&nbsp;' + file.name +
                    '</li>'
                );

                var select = $(this).parents('.lovefactory-dialog-friendship:first').find('.lovefactory-dialog-buttons:first select');
                var value = select.val();
                select.clone().val(value).appendTo(files.find('li:last span:first'));
            }
        }
    });

    $(document).on('click', '.remove-buffer-file', function (event) {
        event.preventDefault();

        var elem = $(this);
        var id = elem.attr('rel');

        elem.parents('li:first').remove();
        delete(bufferUpload[id]);
    });
});
