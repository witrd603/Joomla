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

class BackendModelGroup extends JModelAdmin
{
    protected $option = 'com_lovefactory';
    protected $event_after_save = 'onLoveFactoryGroupAfterSave';

    public function __construct($config = array())
    {
        parent::__construct($config);

        $dispatcher = JEventDispatcher::getInstance();
        $dispatcher->register($this->event_after_save, $this->event_after_save);
    }

    public function getTable($type = 'Group', $prefix = 'Table', $config = array())
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
                $base = 'form_field_group_' . $field->fieldname;

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

        // Check if group approval is enabled.
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if (!$settings->approval_groups) {
            $form->removeField('approved');
        }
    }
}

function onLoveFactoryGroupAfterSave($context, $table, $isNew)
{
    if ('com_lovefactory.group' != $context) {
        return true;
    }

    if (!$isNew) {
        return true;
    }

    $member = JTable::getInstance('GroupMember', 'Table');
    $data = array('user_id' => $table->user_id, 'group_id' => $table->id);

    $member->save($data);

    return true;
}
