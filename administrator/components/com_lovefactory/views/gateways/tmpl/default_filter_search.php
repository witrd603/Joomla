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

<div class="filter-search fltlft">
    <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>

    <input type="text" name="filter_search" id="filter_search"
           value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
           title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"/>

    <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
    <button type="button"
            onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
</div>
