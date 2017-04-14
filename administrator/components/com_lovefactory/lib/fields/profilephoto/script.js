jQuery(document).ready(function ($) {
    $('[data-action="select"]', 'div.ajax-upload-actions').click(function (event) {
        event.preventDefault();

        var $elem = $(this);
        var $ajaxUpload = $elem.parents('div.ajax-upload');
        var $input = $ajaxUpload.find('input[type="file"]');

        $input.click();
    });

    $('[data-action="remove"]', 'div.ajax-upload-actions').click(function (event) {
        event.preventDefault();

        var $elem = $(this);
        var $ajaxUpload = $elem.parents('div.ajax-upload');

        $ajaxUpload.find('div.ajax-upload-photo').html('');
        $ajaxUpload.find('input[type="hidden"]').val('');

        $elem.hide();
    });

    $('input[type="file"]', 'div.ajax-upload').change(function (event) {
        var $elem = $(this);
        var formData = new FormData;
        var url = $('[data-action="select"]', 'div.ajax-upload-actions').attr('href');
        var $parent = $elem.parents('div.ajax-upload');
        var $progress = $parent.find('div.progress');
        var $remove = $parent.find('a[data-action="remove"]');

        formData.append('file', this.files[0]);

        $progress.show();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();

                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function (event) {
                        var percentComplete = event.loaded / event.total * 100;
                        $progress.find('div.bar').css('width', percentComplete + '%');
                    }, false);
                }

                return myXhr;
            },
            complete: function () {
                $progress.hide();
            },
            success: function (response) {
                if (response.status) {
                    $parent.find('div.ajax-upload-photo')
                        .html('<div style="background-image: url(' + response.thumb + '); margin-bottom: 10px;" class="lovefactory-thumbnail"></div>');

                    $parent.find('input[type="hidden"]').val(response.filename);
                    $remove.show();
                }
                else {
                    alert(response.error);
                }
            },
            error: function (jqXHR, textStatus, errorMessage) {
            }
        });
    });
});
