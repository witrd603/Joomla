window.addEvent('domready', function () {
    var elements = ['infobar', 'shoutbox', 'status', 'wallpage', 'members_map', 'search_radius', 'interactions',
        'gmaps', 'profile_friends', 'recaptcha', 'groups', 'chatfactory_integration', 'fields_location',
        'rating', 'youtube_integration'];

    for (var i = 0, count = elements.length; i < count; i++) {
        var element = elements[i];

        $$('#enable_' + element).addEvent('change', function () {

            var id = this.getProperty('id').replace('enable_', '');
            var value = this.getProperty('value');
            var display = (1 == value) ? '' : 'none';

            $$('.' + id + '_related').setStyle('display', display);
        }).fireEvent('change');
    }

    // Custom date format
    $$('#date_format').addEvent('change', function () {
        var value = this.getProperty('value');
        var display = value == 'custom' ? 'inline' : 'none';

        $$('#date_custom_format_wrapper').setStyle('display', display);
    }).fireEvent('change');
});

jQuery(document).ready(function ($) {
    // Buyer Template add button
    var container = $('#invoice_template_buyer').parents('fieldset:first').find('div.editor');

    container.append(
        '<div class="btn-toolbar">' +
        '  <div class="article">' +
        '    <a rel="{handler: \'iframe\', size: {x: 770, y: 400}}" onclick="SqueezeBox.open(this.href, {handler: \'iframe\', size: { x:800, y: 800}}); return false;" href="index.php?option=com_lovefactory&amp;view=fields&amp;tmpl=component&amp;layout=modal&amp;type=invoice" title="' + Joomla.JText._('COM_LOVEFACTORY_SETTINGS_INVOICE_BUYER_ADD_FIELDS') + '" class="modal-button btn">' +
        Joomla.JText._('COM_LOVEFACTORY_SETTINGS_INVOICE_BUYER_ADD_FIELDS') +
        '    </a>' +
        '  </div>' +
        '</div>');

    $('.nav-tabs a').click(function (event) {
        event.preventDefault();

        var elem = $(this);
        var id = elem.attr('href').replace('#', '');

        Cookie.write('com_lovefactory_settings_tab', id);
    });
});
