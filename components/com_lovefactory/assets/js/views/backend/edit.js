jQueryFactory(document).ready(function ($) {
    // Field visibility
    $(".lovefactory-field-visibility").click(function () {
        var link = $(this);
        var id = link.attr("rel");
        var parent = link.parent();
        var hidden = $("#field_visibility_" + id);
        var val = hidden.val();

        link.hide();
        parent.append("<div id='field_visibility_wrapper_" + id + "'><select style='font-size: 10px;' id='select_field_visibility_" + id + "'><option value='0' " + (val == 0 ? 'selected' : '') + ">" + txt_visibilities[0] + "</option><option value='1' " + (val == 1 ? 'selected' : '') + ">" + txt_visibilities[1] + "</option><option value='2' " + (val == 2 ? 'selected' : '') + ">" + txt_visibilities[2] + "</option></select><a href='#' class='lovefactory-button lovefactory-bullet-add'>" + txt_apply + "</a></div>")

        $("#field_visibility_wrapper_" + id + " .lovefactory-bullet-add").unbind().click(function () {
            var value = $("#select_field_visibility_" + id).val();
            hidden.val(value);

            $("#txt_field_visibility_" + id).html(txt_visibilities[value]);

            $("#field_visibility_wrapper_" + id).remove();
            link.show();

            return false;
        });

        return false;
    });
});

function submitbutton(pressbutton) {
    if (pressbutton == 'saveprofile' || pressbutton == 'applyprofile') {
        if (!formValidation()) {
            return false;
        }
    }

    submitform(pressbutton);
}
