jQuery(document).ready(function ($) {
    $('input[type="file"]', 'div.field-images').change(function (event) {
        var url = $('div.field-images').data('url');
        var $currentImage = $(this).parent().find('div.current-image');

        var height = $('input#resize_height').val();
        var width = $('input#resize_width').val();

        var fd = new FormData;
        fd.append('id', $(this).data('id'));
        fd.append('height', height);
        fd.append('width', width);
        fd.append('file', this.files[0]);

        $currentImage.hide().find('img').remove();

        $.ajax({
            url: url,
            type: 'POST',
            data: fd,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            dataType: 'json',
            success: function (response) {
                var time = new Date().getTime();
                $currentImage.show().prepend('<img src="' + response.src + '?t=' + time + '" />');
                $currentImage.find('input[type="hidden"]').val(1);
            }
        });
    });

    $('a.btn-danger', 'div.field-images').click(function (event) {
        event.preventDefault();

        var $elem = $(this);

        $.get($(this).attr('href'), function (response) {
            $elem.parents('div.current-image').hide().find('input[type="hidden"]').val(0);
        }, 'json');
    });
});
