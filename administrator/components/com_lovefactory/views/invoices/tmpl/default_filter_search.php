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

<style>
    div.input-append {
        display: inline;
    }
</style>

<div class="filter-search fltlft">
    <label class="filter-search-lbl" for="filter_search"
           style="display: inline;"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
    <input type="text" name="filter_search" id="filter_search"
           value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
           title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"/>

    <label class="filter-from-lbl" for="filter_from"
           style="display: inline;"><?php echo JText::_('JSEARCH_FILTER_FROM_LABEL'); ?>:</label>
    <?php echo JHtml::calendar($this->escape($this->state->get('filter.from')), 'filter_from', 'filter_from'); ?>

    <label class="filter-to-lbl" for="filter_to"
           style="display: inline;"><?php echo JText::_('JSEARCH_FILTER_TO_LABEL'); ?>:</label>
    <?php echo JHtml::calendar($this->escape($this->state->get('filter.to')), 'filter_to', 'filter_to'); ?>

    <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
    <button type="button"
            onclick="document.id('filter_search').value=''; document.id('filter_to').value=''; document.id('filter_from').value=''; this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
</div>
