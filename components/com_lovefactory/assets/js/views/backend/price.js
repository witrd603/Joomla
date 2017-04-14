jQueryFactory(document).ready(function ($) {
    // Is trial change
    $("#is_trial").change(function () {
        if (1 == $(this).val()) {
            $("#row-hours")
                .find("input").attr("disabled", "").end()
                .show();

            $("#row-months")
                .find("input").attr("disabled", "disabled").end()
                .hide();

            $(".row-price").hide();

            $(".row-trial").show().find("input, select").attr("disabled", "");
        }
        else {
            $("#row-hours")
                .find("input").attr("disabled", "disabled").end()
                .hide();

            $("#row-months")
                .find("input").attr("disabled", "").end()
                .show();

            $(".row-price").show();

            $(".row-trial").hide().find("input, select").attr("disabled", "disabled");
        }

        $("#available_interval").change();
    }).change();


    // Available interval
    $("#available_interval").change(function () {
        if ($(this).attr("disabled")) {
            return false;
        }

        if (1 == $(this).val()) {
            $(".row-available-interval").show().find("input").attr("disabled", "");
        }
        else {
            $(".row-available-interval").hide().find("input").attr("disabled", "disabled");
        }
    }).change();

    // Price unavailable
    $('.price_unavailable').change(function () {
        var $this = $(this);
        var id = $this.attr('id').replace('price_unavailable_', '');
        var input = $('#price_' + id);

        if (this.checked) {
            // Save old value
            input.attr('rel', input.val());

            // Set new value
            input.val(-1);

            // Hide the input
            input.parents('span:first').hide();
        }
        else {
            // Set the saved value
            input.val(input.attr('rel') == '-1.00' ? '0.00' : input.attr('rel'));

            // Show the input
            input.parents('span:first').show();
        }

    }).change();
});
