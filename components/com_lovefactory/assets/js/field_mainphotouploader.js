jQueryFactory(document).ready(function ($) {
    $('.lovefactory-classic-uploader').click(function (event) {
        event.preventDefault();

        var parent = $(this).parents('.lovefactory-main-photo-upload:first');

        parent.find('.flash-uploader, .classic-uploader').toggle();

        $('#main_photo_upload').attr('disabled', $('.flash-uploader').is(':visible') ? '' : 'disabled');
        $('#main_photo_upload_classic').attr('disabled', $('#main_photo_upload_classic').is(':visible') ? '' : 'disabled');
    });
});
