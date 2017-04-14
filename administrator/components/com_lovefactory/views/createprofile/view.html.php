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

use ThePhpFactory\LoveFactory\Factory;

class BackendViewCreateProfile extends LoveFactoryAdminView
{
    protected $page;
    protected $renderer;
    protected $settings;

    public function display($tpl = null)
    {
        $this->settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!array_intersect(JFactory::getUser()->groups, $this->settings->create_profile_admin_groups)) {
            JFactory::getApplication()->enqueueMessage('You are not allowed to access that page!');
            JFactory::getApplication()->redirect('index.php?option=com_lovefactory');
        }

        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';

        JFactory::getLanguage()->load('com_lovefactory', JPATH_SITE);

        JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_lovefactory/models');
        $model = JModelLegacy::getInstance('CreateProfile', 'FrontendModel');

        $this->page = $model->getPage();
        $this->renderer = $this->getRenderer();

        JToolBarHelper::title('Create new profile', 'generic.png');
        JToolBarHelper::apply('create', 'Create new profile');
        JToolBarHelper::cancel();

        parent::display($tpl);
    }

    protected function getRenderer()
    {
        return Factory::buildPageRenderer('editable');
    }
}
