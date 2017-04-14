jQuery(document).ready(function ($) {
    // Remove empty fields.
    $('form.lovefactory-form').submit(function (event) {

        $('input, select', $(this)).each(function (index, element) {
            var $element = $(element);

            if ('' == $element.val()) {
                $element.attr('name', '');
            }
        });

        return true;
    });

    // Submit form on filter change.
    $('select', '.filters', 'form.lovefactory-form').change(function (event) {
        $(this).parents('form:first').submit();
    });

    // Batch select all.
    $('input[type="checkbox"].batch', 'form.lovefactory-form').click(function (event) {
        var elem = $(this);
        var checked = elem.is(':checked');

        $('input[name="batch[]"]').attr('checked', checked);
    });
});
