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

class FrontendModelSettings extends JModelAdmin
{
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm(
            'com_lovefactory.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getTable($name = 'Profile', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    public function save($data)
    {
        $data['user_id'] = JFactory::getUser()->id;

        if (isset($data['params']) && is_array($data['params'])) {
            $params = new JRegistry($data['params']);
            $data['params'] = $params->toString();
        }

        return parent::save($data);
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_lovefactory.edit.settings.data', array());

        if (!$data) {
            $data = $this->getItem(JFactory::getUser()->id);
        }

        JFactory::getApplication()->setUserState('com_lovefactory.edit.settings.data', null);

        return $data;
    }

    protected function preprocessForm(JForm $form, $data, $group = 'content')
    {
        parent::preprocessForm($form, $data, $group);

        $settings = JComponentHelper::getParams('com_lovefactory');
        $this->removeUnupdatableFields($form, $settings);

        LoveFactoryHelper::addFormLabels($form, 'form_settings');
    }

    protected function removeUnupdatableFields($form, $settings)
    {
        foreach ($form->getFieldsets() as $fieldset) {
            foreach ($form->getFieldset($fieldset->name) as $field) {
                $key = 'profile_settings.enable.' . ($field->group ? $field->group . '.' : '') . $field->fieldname;

                if (!$settings->get($key, 1)) {
                    $form->removeField($field->fieldname, $field->group);
                } else {
                    $defaultKey = 'profile_settings.' . ($field->group ? $field->group . '.' : '') . $field->fieldname;
                    $form->setFieldAttribute($field->fieldname, 'default', $settings->get($defaultKey), $field->group);
                }
            }
        }
    }
}
