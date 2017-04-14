jQuery(document).ready(function ($) {
    var open = false;
    var content = $('.description .content');
    var minHeight = 40;

    $('.show-more a').click(function (event) {
        event.preventDefault();

        if (open) {
            content.animate({height: minHeight + 'px'});
        } else {
            content.animate({height: '100%'});
        }
        open = !open;
    });

    if (content.height() > minHeight) {
        content.height(minHeight + 'px');
        $('.show-more').show();
    }
});
