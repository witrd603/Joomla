jQueryFactory(document).ready(function ($) {
    var timer = setInterval(function () {
        $.post(route_infobar_update, {format: "raw"}, function (response) {
            var old;

            // Interactions
            var interactions = $("#lovefactory-bottom-interactions span");
            old = interactions.html();
            interactions.html(response.i);

            if (0 != response.i && undefined != response.i) {
                $("#lovefactory-bottom-interactions span").addClass("bold");

                if (response.i != old) {
                    $("#lovefactory-bottom-interactions .bold").effect("pulsate", {}, 500);
                }
            }
            else {
                $("#lovefactory-bottom-interactions span").removeClass("bold");
            }

            // Messages
            var messages = $("#lovefactory-bottom-messages span");
            old = messages.html();
            messages.html(response.m);

            if (0 != response.m && undefined != response.m) {
                $("#lovefactory-bottom-messages span").addClass("bold");

                if (response.m != old) {
                    $("#lovefactory-bottom-messages .bold").effect("pulsate", {}, 500);
                }
            }
            else {
                $("#lovefactory-bottom-messages span").removeClass("bold");
            }

            // Requests
            var requests = $("#lovefactory-bottom-users span");
            old = requests.html();
            requests.html(response.r);

            if (0 != response.r) {
                $("#lovefactory-bottom-users span").addClass("bold");

                if (response.r != old) {
                    $("#lovefactory-bottom-users .bold").effect("pulsate", {}, 500);
                }
            }
            else {
                $("#lovefactory-bottom-users span").removeClass("bold");
            }
        }, "json");
    }, 1000 * infobar_refresh_interval);

    // Close button
    $("#lovefactory-bottom-bar #lovefactory-infobar-close").click(function () {
        var wrapper = $("#lovefactory-bottom-wrapper");
        var height = wrapper.css("height");

        wrapper.slideUp("slow", function () {
            clearInterval(timer);
            $.post(route_infobar_close, {format: "raw"});
        });

        return false;
    });
});
