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

jimport('joomla.application.component.model');

class FrontendModelDialog extends FactoryModel
{
    public function getUserId()
    {
        return JFactory::getApplication()->input->getInt('user_id', 0);
    }

    public function getUsername()
    {
        $table = $this->getTable('Profile', 'Table');
        $table->load($this->getUserId());

        return $table->display_name;
    }

    public function getParams()
    {
        $params = JFactory::getApplication()->input->get('params', array(), 'array');

        return new JRegistry($params);
    }

    public function getSettings()
    {
        return LoveFactoryApplication::getInstance()->getSettings();
    }

    public function getTest()
    {
        $input = JFactory::getApplication()->input;

        if ('photoupload' === $input->getString('layout') && 1 === $input->getInt('test')) {
            return true;
        }

        return false;
    }
}
