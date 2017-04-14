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

<select name="filter_enabled" class="inputbox" onchange="this.form.submit()">
    <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
    <?php echo JHtml::_('select.options', array(JText::_('JDISABLED'), JText::_('JENABLED')), 'value', 'text', $this->state->get('filter.enabled'), true); ?>
</select>
