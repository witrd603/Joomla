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

jimport('joomla.application.component.controllerform');

class BackendControllerField extends JControllerForm
{
    protected $option = 'com_lovefactory';

    public function update()
    {
        $app = JFactory::getApplication();
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');
        $context = "com_lovefactory.edit.field";

        $app->setUserState($context . '.data', $data);

        $this->setRedirect('index.php?option=com_lovefactory&view=field&id=' . $data['id'], false);
    }
}
