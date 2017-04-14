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

class BackendModelPayment extends FactoryModelAdmin
{
    protected $option = 'com_lovefactory';

    public function getTable($type = 'Payment', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm($this->option . '.' . $this->getName(), $this->getName(), array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.' . $this->getName() . '.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        $ipn = new JRegistry($data->data);
        $data->data = array();

        foreach ($ipn->toArray() as $key => $value) {
            $data->data[] = $key . ' => ' . $value;
        }

        $data->data = implode("\n", $data->data);

        return $data;
    }

    public function changeStatus($pks, $value = 30)
    {
        // Initialise variables.
        JArrayHelper::toInteger($pks);

        foreach ($pks as $i => $pk) {
            $table = $this->getTable();

            if (!$table->load($pk)) {
                $this->setError(JText::sprintf('COM_LOVEFACTORY_PAYMENTS_PAYMENT_NOT_FOUND', $pk));
                return false;
            }

            if (in_array($table->status, array(20, 30))) {
                $this->setError(JText::sprintf('COM_LOVEFACTORY_PAYMENTS_PAYMENT_CANNOT_BE_UPDATED', $pk));
                return false;
            }

            $table->status = $value;
            $table->store();

            $this->setState('completed', $i + 1);
        }

        return true;
    }

    protected function preprocessForm(JForm $form, $data, $group = 'content')
    {
        parent::preprocessForm($form, $data, $group);

        if (in_array($data->status, array(20, 30))) {
            $form->setFieldAttribute('status', 'readonly', 'true');
            $form->setFieldAttribute('status', 'class', 'readonly');
        }

        if ('' == $data->errors) {
            $form->removeField('errors');
        }
    }
}
