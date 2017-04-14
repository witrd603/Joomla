jQuery(document).ready(function ($) {
    // Initialise dashboard.
    $('.column').sortable({
        connectWith: '.column',
        handle: '.header2',
        placeholder: 'ui-state-highlight',
        forcePlaceholderSize: true,
        items: '.box',
        tolerance: 'pointer',
        start: function (event, ui) {
            ui.placeholder
                .css('height', ui.item.css('height'))
                .css('width', ui.item.css('width'));
        },
        stop: function (event, ui) {
            var serialize = '';
            $('.column').each(function (index, element) {
                serialize += $(this).sortable('serialize').replace(/portlet\[\]\=/g, '.').replace(/\&/g, '');

                if (0 == index) {
                    serialize += '/';
                }
            });

            var exdate = new Date();
            exdate.setDate(exdate.getDate() + 365);
            document.cookie = 'lovefactory_dashboard_columns=' + escape(serialize) + '; expires=' + exdate.toUTCString();
        }
    });

    // Minimize / Maximize buttons.
    $('.minimize').click(function (event) {
        event.preventDefault();

        var elem = $(this);
        var content = elem.parents('.box:first').find('.content');
        var id = elem.parents('.box:first').attr('id').replace('portlet_', '');
        var exdate = new Date();

        exdate.setDate(exdate.getDate() + 365);
        document.cookie = 'lovefactory_dashboard_column_' + id + '=' + (elem.hasClass('icon-plus-circle') ? 1 : 0) + '; expires=' + exdate.toUTCString();

        elem.toggleClass('icon-minus-circle icon-plus-circle');
        content.toggle();
    });

    $('.box').each(function (index, element) {
        var elem = $(element);
        var id = elem.attr('id').replace('portlet_', '');
        var minimize = read_cookie('lovefactory_dashboard_column_' + id)

        if (null != minimize && 0 == minimize) {
            elem.find('.minimize').click();
        }
    });


    function read_cookie(k, r) {
        return (r = RegExp('(^|; )' + encodeURIComponent(k) + '=([^;]*)').exec(document.cookie)) ? r[2] : null;
    }
});
