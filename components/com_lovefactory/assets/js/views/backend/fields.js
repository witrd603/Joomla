jQueryFactory(document).ready(function ($) {
    $(document).on('click', 'a[class^="field-modal-"]', function (event) {
        event.preventDefault();

        var elem = $(this);
        var id = elem.attr('class').replace('field-modal-', '');
        var text = elem.html().trim();

        window.parent.jInsertEditorText('<span id="' + id + '">%%' + text + '%%</span>', 'invoice_template_buyer');

        window.parent.SqueezeBox.close();
    });
});
