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

jimport('joomla.application.component.controlleradmin');

class BackendControllerFields extends JControllerAdmin
{
    protected $option = 'com_lovefactory';

    public function getModel($name = 'Field', $prefix = 'BackendModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function sync()
    {
        $model = $this->getModel('Field');
        $model->sync();

        $this->setRedirect('index.php?option=com_lovefactory&view=fields');
        return true;
    }
}
