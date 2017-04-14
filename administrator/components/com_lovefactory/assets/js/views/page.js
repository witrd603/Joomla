jQuery(document).ready(function ($) {
    var sortableOptions = {
        field: {
            items: '.field',
            connectWith: '.fields',
            opacity: .6,
            handle: '.field-handler',
            forcePlaceholderSize: true,
            forceHelperSize: true,
            placeholder: 'field-state-highlight',
            tolerance: 'pointer',
            start: function (event, ui) {
                $('.column').addClass('column-state-available');
            },
            stop: function (event, ui) {
                $('.column').removeClass('column-state-available');
            }
        },
        column: {
            items: '.column',
            axis: 'x',
            opacity: .6,
            handle: '.column-handler',
            forcePlaceholderSize: true,
            forceHelperSize: true,
            placeholder: 'column-state-highlight',
            tolerance: 'pointer'
        },
        zone: {
            axis: 'y',
            opacity: .6,
            handle: '.zone-handler',
            forcePlaceholderSize: true,
            forceHelperSize: true,
            placeholder: 'zone-state-highlight',
            tolerance: 'pointer'
        }
    }

    $('.zones').sortable(sortableOptions.zone);
    $('.columns').sortable(sortableOptions.column);
    $('.fields').sortable(sortableOptions.field);

    $(document).on('click', '.button-add-zone', function (event) {
        event.preventDefault();

        var zones = $('ul.zones');
        var setup = $('#page-setup');
        var zoneHeader = setup.attr('data-zone-header');
        var zoneBody = setup.attr('data-zone-body');

        zones
            .append('<li class="zone">' + zoneHeader + zoneBody + '</li>')
            .find('ul.columns:last').sortable(sortableOptions.column);
    });

    $(document).on('click', '.button-remove-zone', function (event) {
        event.preventDefault();

        $(this).parents('li.zone:first').remove();
    });

    $(document).on('click', '.button-add-column', function (event) {
        event.preventDefault();

        var columns = $(this).parents('li.zone:first').find('ul.columns:first');
        var setup = $('#page-setup');
        var columnHeader = setup.attr('data-column-header');
        var columnBody = setup.attr('data-column-body');

        columns
            .append('<li class="column">' + columnHeader + columnBody + '</li>')
            .find('ul.fields:last').sortable(sortableOptions.field);

        $('fieldset.fieldset-columns').trigger('checkColumns');
    });

    $(document).on('click', '.button-remove-column', function (event) {
        event.preventDefault();

        $(this).parents('li.column:first').remove();
    });

    $(document).on('click', '.button-add-field', function (event) {
        event.preventDefault();

        var elem = $(this);
        var zone = elem.parents('.zone:first');
        var column = elem.parents('.column:first');
        var zoneId = zone.index();
        var columnId = column.index();
        var pageType = $('#page-setup').attr('data-page-type');
        var url = 'index.php?option=com_lovefactory&view=fields&layout=modal&tmpl=component&zone=' + zoneId + '&column=' + columnId + '&type=' + pageType;

        SqueezeBox.open(url, {handler: 'iframe'});
    });

    $(document).on('click', '.button-field-remove', function (event) {
        event.preventDefault();

        $(this).parents('li.field:first').remove();
    });

    $('#page-setup').bind('serialize', function () {
        var setup = $(this);
        var name = setup.attr('data-name');

        setup.append('<input type="hidden" value="" name="' + name + '" />');

        $('.zone').each(function (indexZone, zone) {
            // Append blank zone.
            setup.append('<input type="hidden" value="" name="' + name + '[' + indexZone + ']" />');

            // Parse titles.
            var elem = $(zone);
            elem.find('.titles input').each(function (index, element) {
                var inputName = $(element).attr('name');
                var value = $(element).val();

                setup.append('<input type="hidden" value="' + value + '" name="jform[fields][' + indexZone + '][titles][' + inputName + ']" />');
            });

            // Add fields.
            $('.column', zone).each(function (indexColumn, column) {
                // Append blank column.
                setup.append('<input type="hidden" value="" name="' + name + '[' + indexZone + '][setup][' + indexColumn + ']" />');

                $('.field', column).each(function (indexField, field) {
                    var inputName = name + '[' + indexZone + '][setup][' + indexColumn + '][' + indexField + ']';

                    setup.append('<input type="hidden" value="' + $(field).attr('id') + '" name="' + inputName + '" />');
                });

                // Append column width.
                var columnWidth = $(column).find('select#columns').val();
                var inputName = name + '[' + indexZone + '][columns][' + indexColumn + ']';

                setup.append('<input type="hidden" value="' + columnWidth + '" name="' + inputName + '" />');
            });
        });
    });

    $.extend({
        insertField: function (id, title, zone, column) {
            var fields = $('.zone:eq(' + zone + ') .column:eq(' + column + ') .fields:first');

            var remove = '<i class="factory-icon icon-minus-circle button-field-remove"></i>';
            var move = '<i class="factory-icon icon-arrow-move field-handler"></i>';

            fields.append('<li class="field" id="' + id + '"><a href="index.php?option=com_lovefactory&controller=field&task=edit&id=' + id + '">' + title + '</a>' + move + remove + '</li>');
        }
    });

    $(document).on('checkColumns', 'fieldset.fieldset-columns', function () {
        var $fieldset = $(this),
            columns = 0;

        $fieldset.find('li.column select#columns').each(function (index, element) {
            columns += parseInt($(element).val());
        });

        if (12 < columns) {
            $fieldset.find('div.columns-error').show();
        }
        else {
            $fieldset.find('div.columns-error').hide();
        }
    });

    $(document).on('change', 'select#columns', function () {
        $('fieldset.fieldset-columns').trigger('checkColumns');
    });

    $('fieldset.fieldset-columns').trigger('checkColumns');
});
