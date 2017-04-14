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

jimport('joomla.application.component.view');
jimport('joomla.html.pane');

class BackendViewPrice extends LoveFactoryAdminView
{
    function display($tpl = null)
    {
        $price = $this->get('Data');
        $memberships = $this->get('Memberships');
        $isNew = ($price->id < 1);
        $genders = $this->get('Genders');

        $text = $isNew ? JText::_('New') : JText::_('Edit');
        JToolBarHelper::title(JText::_('Price') . ': <small><small>[ ' . $text . ' ]</small></small>');
        JToolBarHelper::apply();
        JToolBarHelper::save();

        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            JToolBarHelper::cancel('cancel', 'Close');
        }

        JHTML::_('behavior.tooltip');

        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php');
        $settings = new LovefactorySettings();

        $membership_select = JHTML::_('select.genericlist', $memberships, 'membership_id', 'class="inputbox" size="1"', 'id', 'title', $price->membership_id);

        $this->price = $price;
        $this->settings = $settings;
        $this->membership_select = $membership_select;
        $this->genders = $genders;

        JHtmlFactory::jQueryScript();
        JHTML::script('components/com_lovefactory/assets/js/views/backend/price.js');

        parent::display($tpl);
    }
}
