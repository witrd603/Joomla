jQueryFactory(document).ready(function ($) {
    $("#type_id").change(function () {
        var value = $(this).val();
        var vars = {};

        vars['value'] = value;
        vars['format'] = "raw";

        $("#parameters").find("input, textarea, select").each(function () {
            vars['_' + $(this).attr("name")] = $(this).val();
        });

        $(".params-page").prepend('<span class="lovefactory-loader" style="padding-right: 25px;">&nbsp;</span>');

        $.post('index.php?option=com_lovefactory&view=field&format=raw', vars, function (response) {
            $(".params-page").find(".lovefactory-loader").remove();
            $("#parameters").html(response);
        });
    });
});

function submitbutton(pressbutton) {
    var form = document.adminForm;
    var title = form.title.value;

    if (pressbutton == 'save' || pressbutton == 'apply') {
        if (title == '') {
            jQueryFactory("#error-title").show();
            return false;
        }
        jQueryFactory("#error-title").hide();
    }

    submitform(pressbutton);
}
