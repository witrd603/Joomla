jQuery(document).ready(function ($) {
    // Reset form button.
    $('.form-reset, .lovefactory-form-reset').click(function (event) {
        event.preventDefault();

        var form = $(this).parents('form:first');

        form.find('input[type="text"], select').val('');
        form.find('input[type="checkbox"], input[type="radio"]').attr('checked', false);
        form.find('.lovefactory-field-search-multiple').val('').show();
        form.find('.lovefactory-field-search-multiple-wrapper').hide();
        form.find('span[id$="_wrapper"]').hide().next().show();

        form.find('div[class^="lovefactory-slider"]').each(function (index, element) {
            if ($(element).is(':data("ui-LoveFactorySlider")')) {
                $(element).LoveFactorySlider('selectBlank');
            }
        });
    });

    // Show search form.
    $('.toggle-form').click(function (event) {
        event.preventDefault();

        var elem = $(this);

        elem.parents('form:first').find('.lovefactory-search-form').slideDown('slow', function () {
            elem.hide();
        });
    });
});
