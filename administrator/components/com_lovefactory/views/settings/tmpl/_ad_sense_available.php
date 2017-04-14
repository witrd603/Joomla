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

<div id="adsense-available"><?php echo JText::_('SETTINGS_LOADING'); ?></div>

<script>
    window.addEvent('domready', function () {
        updateAvailable();

        function updateAvailable() {
            new Request.HTML({
                url: 'index.php?option=com_lovefactory&controller=adsense&task=getlist&format=raw',
                method: 'get',
                onSuccess: function (response) {

                    $('adsense-available').empty().adopt(response);

                    $$(".adsense-delete").addEvent('click', function (event) {
//		        event = new Event(event);
//            event.stop();

                        new Request({
                            url: 'index.php?option=com_lovefactory&controller=adsense&task=delete&format=raw',
                            method: 'post',
                            data: {id: this.getAttribute("rel")},
                            onSuccess: function () {
                                updateAvailable();
                            }
                        }).send();
                    })
                }
            }).send();
        }

        $("adsense-save").addEvent('click', function (event) {
//      event = new Event(event);
//      event.stop();

            new Request({
                url: 'index.php?option=com_lovefactory&controller=adsense&task=save&format=raw',
                method: 'post',
                data: {
                    title: $("adsense_title").getProperty('value'),
                    script: $("adsense_script").getProperty('value'),
                    rows: $("adsense_rows").getProperty('value')
                },
                onSuccess: function () {
                    updateAvailable();
                }
            }).send();
        });
    });
</script>
