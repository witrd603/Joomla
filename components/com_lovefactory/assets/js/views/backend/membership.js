jQueryFactory(document).ready(function ($) {
    // Unlimited checkbox change
    $("input[id$='unlimited']").change(function () {
        var id = $(this).attr('id').replace('_unlimited', '');
        var input = $('#' + id);

        if ($(this).attr("checked")) {
            input.hide();
            input.attr("rel", input.val());
            input.val(-1);
        }
        else {
            input.show();
            input.val(input.attr("rel") ? input.attr("rel") : input.val());
        }
    }).change();

    $('input[type="checkbox"]', 'div.field-countable-restriction').change(function (event) {
        var $element = $(this),
            $input = $element.parent().next('input[type="text"]'),
            checked = $element.is(':checked');

        if (checked) {
            $input.val(-1).hide();
        }
        else {
            if (-1 == $input.val()) {
                $input.val(0);
            }

            $input.show();
        }
    }).change();
});
