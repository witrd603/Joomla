jQuery(document).ready(function ($) {
    $('#paymentForm').submit(function () {
        var checked = $('input[name="method"]:checked');

        if (!checked.length) {
            return false;
        }

        return true;
    });
});
