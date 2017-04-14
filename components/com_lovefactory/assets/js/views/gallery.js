jQueryFactory(document).ready(function ($) {
    // Report gallery
    $(".lovefactory-bullet-error").click(function () {
        var output = '<div style="position:relative; display: ;">'
            + '  <textarea></textarea>'
            + '  <div class="lovefactory-actions">'
            + '    <a href="#" class="lovefactory-button lovefactory-bullet-add">' + txt_send_message + '</a>'
            + '    <a href="#" class="lovefactory-button lovefactory-bullet-delete">' + txt_cancel + '</a>'
            + '  </div>'
            + '</div>';

        var speed = $("#lovefactory-action").html() == '' ? 0 : "fast";
        var cell = $("#lovefactory-action");

        cell.fadeOut(speed, function () {
            $(this).html("").append(output).fadeIn("fast", function () {
                cell.find("textarea:first").focus();

                // Cancel button
                cell.find(".lovefactory-bullet-delete").click(function () {
                    cell.fadeOut("fast");

                    return false;
                });

                // Send button
                cell.find(".lovefactory-bullet-add").click(function () {
                    var message = cell.find("textarea:first").val();
                    cell.find(".lovefactory-error-field").fadeOut("fast");

                    cell
                        .find("div:first")
                        .append('<div class="lovefactory-loading-cover" style="display: none"></div>')
                        .find(".lovefactory-loading-cover").fadeIn("fast", function () {
                        $.post(route_report, {
                            format: "raw",
                            text: message,
                            gallery_id: gallery_id
                        }, function (response) {
                            cell.find("div:first").fadeOut("fast");
                            $.showMessage({id: "lovefactory-interaction-response", response: response});
                        }, "json");
                    });

                    return false;
                });
            });
        });

        return false;
    });

    $("a.fancybox").fancybox({
        'transitionIn': 'elastic',
        'transitionOut': 'elastic',
        'speedIn': 600,
        'speedOut': 200,
    });

});
