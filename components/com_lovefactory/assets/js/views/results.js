jQuery(document).ready(function ($) {
    $('#filter_order, #filter_order_Dir').change(function (event) {
        $('#adminForm').submit();
    });
});
