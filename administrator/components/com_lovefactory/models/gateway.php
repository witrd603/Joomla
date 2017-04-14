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

jimport('joomla.application.component.modeladmin');

class BackendModelGateway extends JModelAdmin
{
    protected $option = 'com_lovefactory';

    public function getTable($type = 'Gateway', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $title = isset($this->getState('item')->element) ? $this->getState('item')->element : $this->getItem($data['id'])->element;

        JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'payment');
        JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'payment' . DS . 'gateways' . DS . $title);
        JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS . 'fields');

        $form = JForm::getInstance($this->option . '.gateway', 'gateway', array('control' => 'jform'));
        $form->loadFile($title);

        $form->bind($this->loadFormData());

        $language = JFactory::getLanguage();
        $language->load($title, JPATH_COMPONENT_ADMINISTRATOR . DS . 'payment');

        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.gateway.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        $this->setState('item', $item);

        return $item;
    }
}
