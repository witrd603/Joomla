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

class BackendControllerReport extends JControllerForm
{
    protected $option = 'com_lovefactory';

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('save2next', 'save');
    }

    public function save($key = null, $urlVar = null)
    {
        if (!parent::save($key, $urlVar)) {
            return false;
        }

        if ('save2next' == $this->getTask()) {
            $model = $this->getModel();

            $nextId = $model->getNextPendingReportAfter(JFactory::getApplication()->input->getInt('id', 0));

            if (!$nextId) {
                $this->setRedirect(FactoryRoute::view('reports'));
            } else {
                $this->setRedirect('index.php?option=com_lovefactory&controller=report&task=edit&id=' . $nextId);
            }
        }

        return true;
    }
}
