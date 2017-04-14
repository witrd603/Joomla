jQuery(document).ready(function ($) {
    $('select#form_sex').change(function (event) {
        var value = $(this).val(),
            url = $('div[data-type="price"]').data('url');

        $.post(url, {gender: value}, function (response) {
            $('select#formgateway').replaceWith(response);
        });
    });
});
