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

class BackendViewMembership extends LoveFactoryAdminView
{
    protected $form;

    function display($tpl = null)
    {
        $membership = $this->get('Data');
        $isNew = ($membership->id < 1);

        $text = $isNew ? JText::_('MEMBERSHIP_NEW') : JText::_('MEMBERSHIP_EDIT');
        JToolBarHelper::title(JText::_('MEMBERSHIP_PAGE_TITLE') . ': <small><small>[ ' . $text . ' ]</small></small>');
        JToolBarHelper::apply();
        JToolBarHelper::save();

        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            JToolBarHelper::cancel('cancel', 'Close');
        }

        JHTML::_('behavior.tooltip');

        JHTML::stylesheet('components/com_lovefactory/assets/css/main.css');
        JHTML::stylesheet('components/com_lovefactory/assets/css/ui.all.css');

        JHtmlFactory::jQueryScript();
        JHTML::script('components/com_lovefactory/assets/js/views/backend/membership.js');

        $settings = new LovefactorySettings();
        $model = JModelLegacy::getInstance('settings', 'BackendModel');
        $chatfactory = $model->getChatFactory();
        $this->blogfactory = $model->getBlogFactory();

        $this->membership = $membership;
        $this->settings = $settings;
        $this->chatfactory = $chatfactory;

        $languages = JLanguageHelper::getLanguages();
        $this->languages = $languages;

        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'html.php');

        $this->form = $this->get('Form');

        $restrictions = new \Joomla\Registry\Registry($membership->restrictions);
        $membership->restrictions = $restrictions->toArray();

        $this->form->bind($membership);

        parent::display($tpl);
    }
}
