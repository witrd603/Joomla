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

class BackendModelNotification extends JModelAdmin
{
    protected $option = 'com_lovefactory';

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

        $this->setFieldLabels($form);

        $xml = simplexml_load_file(LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'notifications.xml');
        //$xml = new SimpleXMLElement(file_get_contents(LoveFactoryApplication::getInstance()->getPath('component_administrator').DS.'notifications.xml'));

        $type = $form->getValue('type') ? $form->getValue('type') : (isset($data['type']) ? $data['type'] : '');
        $notification = $xml->xpath('//notifications//notification[@type="' . $type . '"]');

        if (!$notification || !$notification[0]->attributes()->admin) {
            $form->removeField('groups');
        }

        return $form;
    }

    public function save($data)
    {
        // Alter the title and published state for Save as Copy
        if (JFactory::getApplication()->input->getCmd('task') == 'save2copy') {
            $orig_data = JFactory::getApplication()->input->get('jform', array(), 'array');
            $orig_table = clone($this->getTable());
            $orig_table->load((int)$orig_data['id']);

            if ($data['subject'] == $orig_table->subject) {
                $data['subject'] .= ' ' . JText::_('JGLOBAL_COPY');
                $data['published'] = 0;
            }
        }

        return parent::save($data);
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $context = $this->option . '.edit.' . $this->getName() . '.data';
        $data = JFactory::getApplication()->getUserState($context, array());

        if (empty($data)) {
            $data = $this->getItem();

            $data->subject = preg_replace('/%%(.+)%%/U', '{{ $1 }}', $data->subject);
            $data->body = preg_replace('/%%(.+)%%/U', '{{ $1 }}', $data->body);
        }

        JFactory::getApplication()->setUserState($context, null);

        return $data;
    }

    protected function setFieldLabels(&$form)
    {
        // Set the labels and descriptions in case they are not set.
        foreach ($form->getFieldsets() as $fieldset) {
            foreach ($form->getFieldset($fieldset->name) as $field) {
                $label = $form->getFieldAttribute($field->fieldname, 'label', '', $field->group);
                $desc = $form->getFieldAttribute($field->fieldname, 'description', '', $field->group);
                $base = 'form_' . $this->getName() . '_' . $field->fieldname;

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
