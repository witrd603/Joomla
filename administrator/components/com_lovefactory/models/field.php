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

class BackendModelField extends JModelAdmin
{
    protected $event_after_delete = 'onLoveFactoryFieldAfterDelete';
    protected $event_after_save = 'onLoveFactoryFieldAfterSave';
    protected $option = 'com_lovefactory';

    public function __construct($config = array())
    {
        parent::__construct($config);

        $dispatcher = JEventDispatcher::getInstance();

        $dispatcher->register($this->event_after_save, $this->event_after_save);
        $dispatcher->register($this->event_after_delete, $this->event_after_delete);

    }

    public function getTable($type = 'Field', $prefix = 'Table', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        JForm::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models/fields');

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

        // Load field specific configuration.
        $type = isset($data['type']) ? $data['type'] : $form->getValue('type', '');
        if ($type) {
            jimport('joomla.filesystem.file');

            $config = LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'lib' . DS . 'fields' . DS . strtolower($type) . DS . 'config.xml';
            if (JFile::exists($config)) {
                $config = file_get_contents($config);

                $form->load($config);
                $form->bind(array('params' => $this->loadFormData()->params));
            }

            // Instantiate new field.
            $field = LoveFactoryField::getInstance($type);
            if (!$field->getGeneratesVisibilityColumn()) {
                $form->removeField('user_visibility');
            }

            if ($field->isPublic()) {
                $form->removeField('visibility');
            }
        }

        // Get subparams.
        $fieldsets = $form->getFieldsets();
        if (isset($fieldsets['params'])) {
            $form->subparams = array();
            $fieldset = $fieldsets['params'];

            if (isset($fieldset->subparams)) {
                $form->subparams = explode(',', $fieldset->subparams);
            }
        }

        // Set the labels and descriptions in case they are not set.
        foreach ($form->getFieldsets() as $fieldset) {
            foreach ($form->getFieldset($fieldset->name) as $field) {
                $label = $form->getFieldAttribute($field->fieldname, 'label', '', $field->group);
                $desc = $form->getFieldAttribute($field->fieldname, 'description', '', $field->group);
                $base = 'form_field_' . $type . '_' . $field->fieldname;

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

        return $form;
    }

    public function sync()
    {
        JLoader::register('TableField', LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'tables' . DS . 'field.php');

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('f.*')
            ->from('#__lovefactory_fields f');
        $results = $dbo->setQuery($query)
            ->loadObjectList('id', 'TableField');

        $dispatcher = JEventDispatcher::getInstance();

        foreach ($results as $result) {
            $dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, &$result, false));
        }
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
}

function onLoveFactoryFieldAfterDelete($event, $table)
{
    JLoader::register('LoveFactoryField', LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'lib' . DS . 'fields' . DS . 'field.php');
    $dbo = $table->getDbo();
    $field = LoveFactoryField::getInstance($table->type, $table);

    // Get profiles table columns
    $profileColumns = $table->getDbo()->getTableColumns('#__lovefactory_profiles', false);

    // Check if fields generates data column.
    if ($field->getGeneratesDataColumn()) {
        $column = $field->getProfileTableColumnName();

        // Check if field has data columns.
        if (array_key_exists($column, $profileColumns)) {
            // Generate data column.
            $query = $field->getQueryAlterProfileTableDropColumn($dbo);
            $table->getDbo()->setQuery($query)->query();
        }
    }

    // Check if fields generates visibility column.
    if ($field->getGeneratesVisibilityColumn()) {
        $column = $field->getVisibilityId();

        // Check if field has data columns.
        if (array_key_exists($column, $profileColumns)) {
            // Generate data column.
            $query = $field->getQueryAlterProfileTableDropVisibilityColumn($dbo);
            $table->getDbo()->setQuery($query)->query();
        }
    }

    return true;
}

function onLoveFactoryFieldAfterSave($event, $table, $isNew)
{
    JLoader::register('LoveFactoryField', LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'lib' . DS . 'fields' . DS . 'field.php');
    $dbo = $table->getDbo();
    $field = LoveFactoryField::getInstance($table->type, $table);

    // Get profiles table columns
    $profileColumns = $dbo->getTableColumns('#__lovefactory_profiles', false);

    // Check if fields generates data column.
    if ($field->getGeneratesDataColumn()) {
        $column = $field->getProfileTableColumnName();

        // Check if field has data columns.
        if (!array_key_exists($column, $profileColumns)) {
            // Generate data column.
            $query = $field->getQueryAlterProfileTableInsertColumn($dbo);
            $dbo->setQuery($query)
                ->query();
        }
    }

    // Check if fields generates visibility column.
    if ($field->getGeneratesVisibilityColumn()) {
        $column = $field->getVisibilityId();

        // Check if field has data columns.
        if (!array_key_exists($column, $profileColumns)) {
            // Generate data column.
            $query = $field->getQueryAlterProfileTableInsertVisibilityColumn($dbo);
            $dbo->setQuery($query)
                ->query();
        }
    }

    return true;
}
