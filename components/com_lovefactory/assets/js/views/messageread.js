jQueryFactory(document).ready(function ($) {
    // Quick reply
    $("#lovefactory-quick-reply").click(function () {
        var output = '<div style="position:relative; display: ;">'
            + '  <div id="lovefactory-quick-error" class="lovefactory-error-field" style="display: none;"><span class="lovefactory-button lovefactory-bullet-error">' + txt_enter_message + '</span></div>'
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

                    if ('' == message) {
                        cell.find(".lovefactory-error-field").fadeIn("fast");
                        return false;
                    }

                    cell
                        .find("div:first")
                        .append('<div class="lovefactory-loading-cover" style="display: none"></div>')
                        .find(".lovefactory-loading-cover").fadeIn("fast", function () {
                        $.post(route_send_message, {
                            format: "raw",
                            text: message,
                            user_id: user_id,
                            reply_to: id
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

    // Report button
    $("#lovefactory-message-report").click(function () {
        var output = '<div style="position:relative; display: ;">'
            + '  <div id="lovefactory-quick-error" class="lovefactory-error-field" style="display: none;"><span class="lovefactory-button lovefactory-bullet-error">' + txt_enter_message + '</span></div>'
            + '  <textarea></textarea>'
            + '  <div class="lovefactory-actions">'
            + '    <a href="#" class="lovefactory-button lovefactory-bullet-add">' + txt_send_message + '</a>'
            + '    <a href="#" class="lovefactory-button lovefactory-bullet-delete">' + txt_cancel + '</a>'
            + '  </div>'
            + '</div>';

        var speed = $("#lovefactory-action").html() == '' ? 0 : "fast";
        var cell = $("#lovefactory-action");
        var link = $(this);

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
                        $.post(route_send_report, {format: "raw", text: message, id: id}, function (response) {

                            cell.find("div:first").fadeOut("fast");
                            $.showMessage({id: "lovefactory-interaction-response", response: response});

                            if (1 == response.status) {
                                link.fadeOut("fast");
                            }
                        }, "json");
                    });

                    return false;
                });
            });
        });

        return false;
    });

    // Add to blacklist button
    $("#lovefactory-message-ignore").unbind().click(function () {
        var link = $(this);

        link.addClass("lovefactory-loader-button");
        $("#lovefactory-action-report").hide().removeClass("lovefactory-action-error");

        $.post(route_blacklist, {format: "raw", user_id: user_id}, function (response) {
            link.removeClass("lovefactory-loader-button");

            $.showMessage({id: "lovefactory-interaction-response", response: response});

            if (1 == response.status) {
                link.fadeOut("fast");
            }

        }, "json");
        return false;
    });
});
