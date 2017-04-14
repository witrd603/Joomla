jQuery(document).ready(function ($) {
    $('a[data-rel="privacy"]').click(function (event) {
        event.preventDefault();

        $(this).hide().next().show();
    });
});
