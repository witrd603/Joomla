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
    .filter-select select {
        width: 150px;
    }
</style>

<fieldset id="filter-bar">
    <div class="filter-search fltlft">
        <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
        <input type="text" name="filter_search" id="filter_search"
               value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
               title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>"/>

        <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
        <button type="button"
                onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
    </div>

    <div class="filter-select fltrt">

        <select name="filter_type" class="inputbox" onchange="this.form.submit()">
            <option value=""><?php echo FactoryText::_('notifications_filter_type_label'); ?></option>
            <?php echo JHtml::_('select.options', $this->get('FilterType'), 'value', 'text', $this->state->get('filter.type')); ?>
        </select>

        <select name="filter_language" class="inputbox" onchange="this.form.submit()">
            <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE'); ?></option>
            <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language')); ?>
        </select>

        <select name="filter_published" class="inputbox" onchange="this.form.submit()">
            <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
            <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false, 'trash' => false, 'all' => false)), 'value', 'text', $this->state->get('filter.published'), true); ?>
        </select>

    </div>
</fieldset>
<div class="clr"></div>
