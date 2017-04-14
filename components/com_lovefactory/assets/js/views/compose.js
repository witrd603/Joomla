jQuery(document).ready(function ($) {
    $("#message_receiver").tokenInput(LoveFactory.route('searchUser'), {
        tokenLimit: 1,
        hintText: Joomla.JText._('COM_LOVEFACTORY_COMPOSE_AUTOCOMPLETE_HINT'),
        noResultsText: Joomla.JText._('COM_LOVEFACTORY_COMPOSE_AUTOCOMPLETE_NO_RESULTS'),
        searchingText: Joomla.JText._('COM_LOVEFACTORY_COMPOSE_AUTOCOMPLETE_SEARCHING'),
        prePopulate: [LoveFactory.get('receiver')]
    });
});
