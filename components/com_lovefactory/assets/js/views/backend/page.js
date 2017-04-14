jQueryFactory(document).ready(function ($) {

    // Sortable button
    $("#sortable").click(function () {

        $(".fields").sortable({
            connectWith: '.fields',
            items: 'li.field',
            placeholder: 'field-state-highlight',
            handle: '.field-handle',
            tolerance: 'pointer',
            opacity: 0.6,
            distance: 10,
            cursorAt: {top: -5, left: 50},
            forcePlaceholderSize: true,
            forceHelperSize: true
        }).disableSelection();

        $("#zones").sortable({
            placeholder: 'zone-state-highlight',
            items: 'li.zone',
            tolerance: 'pointer',
            axis: 'y',
            handle: '.zone-handle',
            opacity: 0.6,
            distance: 10,
            forcePlaceholderSize: true,
            forceHelperSize: true
        });
    });

    $(".zone-handle, .field-handle").disableSelection();

    // Delete column
    $(document)
        .on("mouseover", ".delete-column", function () {
            var parent = $(this).parent().parent();
            parent.find("ul").addClass("delete");
        })
        .on("mouseout", ".delete-column", function () {
            var parent = $(this).parent().parent();
            parent.find("ul").removeClass("delete");
        })
        .on("click", ".delete-column", function () {
            var parent = $(this).parent().parent();
            $("#available").append(parent.find("ul").html());
            parent.remove();

            return false;
        })

    // Add column
    $(document).on("click", ".add-column", function () {
        var parent = $(this).parents(".table").find("tbody tr");

        parent.append("<td><ul class='fields'></ul><div><a href='#' class='lovefactory-button lovefactory-bullet-delete delete-column'>" + txt_delete_column + "</a><a href='#' class='lovefactory-button lovefactory-bullet-add add-spacer'>" + txt_add_spacer + "</a></div></td>");
        $("#sortable").click();

        return false;
    });

    // Delete zone
    $(document).on("click", ".delete-zone", function () {
        var parent = $(this).parents(".zone");
        parent.find(".delete-column").click();
        parent.remove();

        return false;
    });

    // Add zone
    $("#add-zone").click(function () {
        var zones = $("#zones");

        var translations = '';

        if (languages.length) {
            translations += '<fieldset><legend>TRANSLATION</legend>';

            for (var i = 0, count = languages.length; i < count; i++) {
                var language = languages[i];

                translations += '<label>' + language + '</label>'
                    + '<input name="translation[' + language + '][title][]" />';
            }

            translations += '</fieldset>';
        }

        zones.append("<li class='zone'><table cellpadding='0px' cellspacing='0px' class='table'><thead><tr><th colspan='10'><span class='zone-handle lovefactory-button lovefactory-go'>" + txt_drag_zone + "</span>" + txt_zone_title + ": <input value='' />" + translations + "</th></tr></thead><tfoot><tr><td colspan='10'><a href='#' class='lovefactory-button lovefactory-bullet-add add-column'>" + txt_add_column + "</a><a href='#' class='lovefactory-button lovefactory-bullet-delete delete-zone'>" + txt_delete_zone + "</a></td></tr></tfoot><tbody><tr></tr></tbody></table></li>");

        return false;
    });

    // Add spacer
    $(document).on('click', '.add-spacer', function (event) {
        event.preventDefault();

        var parent = $(this).parents('td:first').find('ul');

        parent.append('<li class="field system" id="field-' + spacer_id + '"><span class="field-handle lovefactory-button lovefactory-go">drag</span><a href="' + spacer_link + '">Spacer</a></li>');
    });

    $("#sortable").click();
});

Joomla.submitbutton = function (pressbutton) {
    var form = document.adminForm;

    if (pressbutton == 'save' || pressbutton == 'apply') {
        var title_string = '';
        var fields_string = '';

        jQueryFactory("#zones li.zone").each(function (index) {
            var zone = jQueryFactory(this);

            title_string += index + "___" + zone.find("input").val() + "###";

            zone.find("ul.fields").each(function (index2) {
                jQueryFactory(this).find("li").each(function () {
                    var id = jQueryFactory(this).attr("id").replace('field-', '');

                    fields_string += index + '_' + index2 + '_' + id + '#';
                })
            });
        });

        fields_string = fields_string.substr(0, fields_string.length - 1);
        title_string = title_string.substr(0, title_string.length - 3);

        jQueryFactory("#selected-fields").val(fields_string);
        jQueryFactory("#titles").val(title_string);

        //console.log(title_string);
    }

    //return false;

    Joomla.submitform(pressbutton);
}
