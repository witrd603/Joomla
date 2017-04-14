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

class BackendModelPage extends JModelAdmin
{
    protected $option = 'com_lovefactory';

    public function getTable($type = 'Page', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        /* @var $form JForm */
        // Get the form.
        $form = $this->loadForm(
            $this->option . '.' . $this->getName(),
            $this->getName(),
            array(
                'control' => 'jform',
                'load_data' => $loadData,
            ));

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

        JFactory::getApplication()->setUserState('com_lovefactory.edit.field' . '.data', null);

        return $data;
    }

    protected function preprocessForm(JForm $form, $data, $group = 'content')
    {
        parent::preprocessForm($form, $data, $group);

        // Set the labels and descriptions in case they are not set.
        foreach ($form->getFieldsets() as $fieldset) {
            foreach ($form->getFieldset($fieldset->name) as $field) {
                $label = $form->getFieldAttribute($field->fieldname, 'label', '', $field->group);
                $desc = $form->getFieldAttribute($field->fieldname, 'description', '', $field->group);
                $base = 'form_field_page_' . $field->fieldname;

                if ('' == $label) {
                    $label = FactoryText::_($base . '_label');
                    $form->setFieldAttribute($field->fieldname, 'label', $label, $field->group);
                }

                if ('' == $desc) {
                    $desc = FactoryText::_($base . '_desc');
                    $form->setFieldAttribute($field->fieldname, 'description', $desc, $field->group);
                }
            }
        }
    }
}
