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

class FrontendViewModuleUsersStatistics extends JViewLegacy
{
    public function display($tpl = null)
    {
        jimport('joomla.filesystem.file');

        $this->items = $this->get('Items');
        $this->moduleId = $this->get('ModuleId');
        $this->moduleClass = $this->get('ModuleClass');

        $language = JFactory::getLanguage();
        $default = JUri::root() . 'components/com_lovefactory/assets/images/userstatistics/user.png';

        foreach ($this->items->genders as $gender) {
            // Set icon.
            $file = JPATH_SITE . '/components/com_lovefactory/assets/images/userstatistics/' . strtolower($gender->sex) . '.png';
            if (JFile::exists($file)) {
                $gender->icon = JUri::root() . 'components/com_lovefactory/assets/images/userstatistics/' . strtolower($gender->sex) . '.png';
            } else {
                $gender->icon = $default;
            }

            // Set language string.
            $key = 'MOD_LOVEFACTORY_USERSSTATISTICS_GENDER_' . strtoupper($gender->sex);

            if ($language->hasKey($key)) {
                $gender->genderName = JText::_($key);
            }
        }

        parent::display($tpl);
    }
}
