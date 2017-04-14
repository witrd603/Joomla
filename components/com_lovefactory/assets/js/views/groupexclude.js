jQueryFactory.fn.extend({
    update: function (options) {
        var $this = jQueryFactory(this);
        var tbody = jQueryFactory('tbody', $this);
        var tfoot = jQueryFactory('tfoot', $this);
        var height = tbody.outerHeight();

        tbody.html('<tr class="loading"><td colspan="20" style="height: ' + height + 'px;"></td></tr>');

        jQueryFactory.get(options.url, function (response) {
            tfoot.remove();
            tbody.remove();
            $this.append(response);

            options.afterUpdate();
        });
    }
});

jQueryFactory(document).ready(function ($) {
    var expandableOptions = {
        slicePoint: 120,
        expandText: txt_read_more,
        expandEffect: 'show',
        userCollapseText: txt_read_less,
    }
    var updateUrl = route_update;

    // Init
    $('.expandable').expander(expandableOptions);
    $('a', '.lf-group-actions').ajaxAction();

    // No caching for ajax calls
    $.ajaxSetup({cache: false});

    // Pagination
    $(document).on('click', 'td.lf-pagination a', function (event) {
        event.preventDefault();

        $('.lf-table').update({
            url: this.href,
            afterUpdate: function () {
                $('.expandable').expander(expandableOptions);
                $('a', '.lf-group-actions').ajaxAction();
            }
        });
    });

    // Sort by change
    $('#sort_groups').change(function () {
        $.cookie('lf-groupexclude-sortby', this.value);

        $('.lf-table').update({
            url: updateUrl,
            afterUpdate: function () {
                $('.expandable').expander(expandableOptions);
                $('a', '.lf-group-actions').ajaxAction();
            }
        });
    });

    // Sort order change
    $(document).on('click', '#sort_order', function (event) {
        event.preventDefault();

        var value = 'asc' == this.title ? 1 : 0;

        $.cookie('lf-groupexclude-sortorder', value);

        if (value) {
            this.title = 'desc';
            this.innerHTML = txt_desc;
            $(this).removeClass('lovefactory-asc').addClass('lovefactory-desc');
        }
        else {
            this.title = 'asc';
            this.innerHTML = txt_asc;
            $(this).removeClass('lovefactory-desc').addClass('lovefactory-asc');
        }

        $('.lf-table').update({
            url: updateUrl,
            afterUpdate: function () {
                $('.expandable').expander(expandableOptions);
                $('a', '.lf-group-actions').ajaxAction();
            }
        });
    });
});
