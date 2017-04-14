<?php

/**
-------------------------------------------------------------------------
lovefactory - Love Factory 4.4.7
-------------------------------------------------------------------------
 * @author thePHPfactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thePHPfactory.com
 * Technical Support: Forum - http://www.thePHPfactory.com/forum/
-------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

?>

<div class="progress progress-striped active">
    <div class="bar" style="width: 0%;"></div>
</div>

<ul style="padding: 0; margin: 0;" class="actions"
    data-url="<?php echo JRoute::_('index.php?option=com_lovefactory&controller=import&task=migrate&format=raw&adaptor=' . $this->adaptor->getAdaptor(), false, -1); ?>">
    <li>Initializing</li>
</ul>

<script>
    jQuery(document).ready(function ($) {
        var actions = $('.actions');
        var bar = $('.progress .bar');
        var url = actions.attr('data-url');

        actions.bind('update', function () {
            $.get(url, function (response) {
                if (!response.finished) {
                    actions.trigger('update');
                }

                actions.prepend('<li>' + response.message + '</li>');
                bar.css('width', response.percent + '%');
            }, 'json').fail(function (jqXHR, textStatus) {
                actions.prepend('<li><div class="alert alert-block"><h4>Error!</h4><code>' + jqXHR.responseText + '</code></div></li>');
            });
        });

        actions.trigger('update');
    });
</script>
