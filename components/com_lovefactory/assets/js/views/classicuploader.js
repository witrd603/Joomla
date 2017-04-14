jQueryFactory(document).ready(function ($) {
    // Add new file input
    $(document).on('change', 'input[type="file"]:last', function (event) {
        var elem = $(this);
        var table = $('.lovefactory-uploads tbody');
        var count = $('.lovefactory-uploads input[type="file"]').length;

        if (count < LoveFactoryUploadLimit || -1 == LoveFactoryUploadLimit) {
            table.append('<tr><td><input type="file" name="file[]" /></td></tr>');
            elem.parent().append('<a href="#" class="lovefactory-button lovefactory-delete file-delete"></a>');
        } else {
            elem.parent().append('<a href="#" class="lovefactory-button lovefactory-delete file-delete"></a>');
        }
    });

    // File input delete
    $(document).on('click', '.file-delete', function (event) {
        event.preventDefault();

        var elem = $(this);
        var parent = elem.parents('tr:first');
        var count = $('.lovefactory-uploads input[type="file"]').length;

        parent.remove();

        if (count == LoveFactoryUploadLimit && $('input[type="file"]:last').val() != '') {
            var table = $('.lovefactory-uploads tbody');
            table.append('<tr><td><input type="file" name="file[]" /></td></tr>');
        }
    });
});
