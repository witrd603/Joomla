(function ($) {
    // Get photo template
    $.getPhotoTemplate = function (options) {
        output = '<li id="lf-media-' + options.id + '" class="lf-media">'
            + '<div class="lovefactory-thumbnail lovefactory-gallery-thumbnail" style="background-image: url(\'' + options.thumbnail + '\');">'

            + '<span class="lf-photo-actions">'
            + '<input type="checkbox" class="lf-checkbox" value="' + options.id + '" />'
            + (!options.approved ? '<span class="lovefactory-button lovefactory-exclamation"></span>' : '')
            + '</span>'

            + '</div>'
            + '<div class="lf-media-actions">'
            + '<a href="#" style="visibility: hidden;" class="lovefactory-button lovefactory-bullet-delete">' + txt_delete + '</a>'
            + '</div>'
            + '</li>';

        return output;
    };

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
    var media_types = ['public', 'friends', 'private'];

    // Sortable lists
    $(".sortable").sortable({
        placeholder: 'ui-state-highlight',
        connectWith: '.sortable',
        tolerance: 'pointer',
        start: function (event, ui) {
            ui.item.parents(".lf-list").find(".lf-media-actions").hide();
            ui.item.parents(".lf-list").find(".lf-photo-actions").hide();
        },
        stop: function (event, ui) {
            ui.item.parents(".lf-list").find(".lf-media-actions").show();
            ui.item.parents(".lf-list").find(".lf-photo-actions").show();

            // If photo is avatar and moved from public
            if (ui.item.hasClass("lf-avatar")) {
                var target = ui.item.parents(".lf-media-container:first").attr("id");
                var photo_id = ui.item.attr("id").replace("lf-media-", "");

                target = target.split("-");
                target = target[1];

                if ('public' != target) {
                    ui.item.removeClass("lf-avatar");
                    $.post(
                        route_remove_avatar,
                        {photo_id: photo_id},
                        function (response) {
                        }, "json");
                }
            }

            $(".lf-media-actions").css("visibility", "hidden");

            if (null != request) {
                request.abort();
            }

            var serialize = '';
            $('.sortable').each(function () {
                var id = $(this).parents('li.lf-media-container:first').attr('id').replace('lf-media-', '');

                var temp = $(this).sortable("serialize", {key: id + '[]'});
                if ('' != temp) {
                    serialize += ('' == serialize ? '' : '&') + temp;
                }
            });

            request = $.post(
                route_save_order,
                {serialize: serialize, media_type: 'photo'},
                function (response) {
                }, "json");
        }
    }).disableSelection();

    // Progress bar
    $(".lf-progressbar").progressbar({value: 0});

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
            if (!numFilesQueued) {
                return false;
            }

            var info = $(this).parents(".lf-media-container").find(".lf-info");

            info
                .show()
                .find(".lf-total")
                .html(numFilesQueued)
                .end()
                .find(".lf-current")
                .html(0)
                .end();

            $(this).swfupload('startUpload');
        },
        uploadStart: function (event, file) {
            var progress = $(this).parents(".lf-media-container").find(".lf-progressbar");
            var info = $(this).parents(".lf-media-container").find(".lf-info");

            progress.progressbar("option", "value", 0).show();
            info.find(".lf-current").html(parseInt(info.find(".lf-current").html()) + 1);
            info.find(".lf-current-file").html(file.name);
        },
        uploadProgress: function (event, file, bytesLoaded) {
            var progress = $(this).parents(".lf-media-container").find(".lf-progressbar");

            progress.progressbar("option", "value", Math.floor(bytesLoaded * 100 / file.size));
        },
        uploadSuccess: function (event, file, serverData) {
            var progress = $(this).parents(".lf-media-container").find(".lf-progressbar");

            progress.progressbar("option", "value", 0);

            try {
                var response = eval('(' + serverData + ')');
            }
            catch (e) {
                alert(txt_error_uploading);
                return false;
            }

            // Upload was successfull
            if (1 == response.status) {
                var html = $.getPhotoTemplate({
                    id: response.photo_id,
                    thumbnail: response.thumbnail,
                    approved: response.approved
                });
                $('#lf-media-' + response.type + ' .sortable')
                    .find('li:last').replaceWith(html).fadeIn().end()
                    .append('<li></li>');
                return true;
            }
            else {
                if (2 == response.code) {
                    $(this).swfupload('cancelUpload');
                    window.location.href = route_redirect;
                }
                else {
                    // Upload failed
                    alert(response.message);
                }
            }
        },
        uploadComplete: function (event, file) {
            var progress = $(this).parents(".lf-media-container").find(".lf-progressbar");
            var info = $(this).parents(".lf-media-container").find(".lf-info");

            if (info.find(".lf-total").html() == info.find(".lf-current").html()) {
                progress.hide();
                info.hide();
            }
            else {
                $(this).swfupload('startUpload');
            }
        },
        uploadError: function (event, file, errorCode, message) {
        }
    };

    $('.lovefactory-picture_add').bindAll(listeners);

    // Upload buttons
    $(".lovefactory-picture_add").each(function () {
        var type = $(this).parents('.lf-media-container').attr('id').replace('lf-media-', '');

        $(this).swfupload({
            upload_url: route_upload,
            file_size_limit: photos_max_size,
            file_types: "*.jpg;*.gif;*.png;",
            file_types_description: "Images",
            file_upload_limit: "0",
            file_queue_limit: "0",
            file_post_name: "photo",
            flash_url: root + "components/com_lovefactory/assets/swfs/swfupload.swf",
            button_image_url: root + 'components/com_lovefactory/assets/images/buttons/picture_add_swf.png',
            button_width: 100,
            button_height: 25,
            button_text: '<span class="theFont">' + txt_upload + '</span>',
            button_text_style: ".theFont { text-align: left; margin-left: 20px; font-size: 12px; font-family: Helvetica,Arial,sans-serif; font-weight: bold; }",
            button_placeholder: $(".lf-button", this)[0],
            button_window_mode: 'transparent',
            button_cursor: SWFUpload.CURSOR.HAND,
            debug: swfupload_debug,
            post_params: {"PHPSESSID": session_id, session_name: session_id, type: type}
        });
    });

    // Set as avatar
    $(".lovefactory-image-star").click(function (event) {
        event.preventDefault();

        var media_ids = $(this).getChecked();

        if (false == media_ids) {
            alert(txt_select_photo);
            return false;
        }

        var link = $(this);
        link.addClass("lovefactory-bullet-load");

        $.post(
            route_set_avatar,
            {media_ids: media_ids},
            function (response) {
                link.removeClass("lovefactory-bullet-load");

                if (0 == response.status) {
                    alert(response.message);
                    return false;
                }

                $(".lf-avatar").removeClass("lf-avatar");
                $("#lf-media-" + response.main_id)
                    .addClass("lf-avatar")
                    .find(".lf-checkbox")
                    .attr("checked", "")
                    .end();
            }, "json");
    });

    // Add gravatar
    $('.lovefactory-gravatar').click(function (event) {
        event.preventDefault();

        var type = $(this).parents('li.lf-media-container:first').attr('id').replace('lf-media-', '');
        var url = route_add_gravatar;
        var data = {type: type};

        $.post(url, data,
            function (response) {
                // Upload was successfull
                if (1 == response.status) {
                    var html = $.getPhotoTemplate({id: response.photo_id, thumbnail: response.thumbnail});
                    $('#lf-media-' + response.type + ' .sortable')
                        .find('li:last').replaceWith(html).fadeIn().end()
                        .append('<li></li>');
                    return true;
                }
                else {
                    if (2 == response.code) {
                        window.location.href = route_redirect;
                    }
                    else {
                        // Upload failed
                        alert(response.message);
                    }
                }
            }, 'json');
    });
});
