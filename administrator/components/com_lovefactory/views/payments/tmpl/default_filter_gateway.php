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

<select name="filter_gateway" class="inputbox" onchange="this.form.submit()">
    <option value=""><?php echo JText::_('COM_LOVEFACTORY_PAYMENTS_FILTER_GATEWAY_LABEL'); ?></option>
    <?php echo JHtml::_('select.options',
        $this->filterGateway,
        'value',
        'text',
        $this->state->get('filter.gateway'),
        true); ?>
</select>
