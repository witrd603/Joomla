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

class BackendModelReport extends JModelAdmin
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

        // Set the labels and descriptions in case they are not set.
        foreach ($form->getFieldsets() as $fieldset) {
            foreach ($form->getFieldset($fieldset->name) as $field) {
                $label = $form->getFieldAttribute($field->fieldname, 'label', '', $field->group);
                $desc = $form->getFieldAttribute($field->fieldname, 'description', '', $field->group);
                $base = 'form_field_report_' . $field->fieldname;

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

    public function save($data)
    {
        if (!parent::save($data)) {
            return false;
        }

        // Delete item action.
        if (isset($data['actions']['delete_item']) && $data['actions']['delete_item']) {
            $this->deleteItem($data);
        }

        // Ban user action.
        if (isset($data['actions']['ban_user']) && $data['actions']['ban_user']) {
            $table = $this->getTable('Profile');

            $table->user_id = $data['user_id'];
            $table->banned = 1;

            $table->store();
        }

        // Send message action.
        if (isset($data['actions']['send_message']) && '' != $data['actions']['send_message']) {
            $table = $this->getTable('LoveFactoryMessage');

            $table->sendSystemMessage($data['user_id'], '', $data['actions']['send_message']);
        }

        return true;
    }

    public function getNextPendingReportAfter($id)
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('r.id')
            ->from('#__lovefactory_reports r')
            ->where('r.status = ' . $dbo->quote(0));
        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $state = $this->option . '.edit.' . $this->getName() . '.data';
        $data = JFactory::getApplication()->getUserState($state, array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        JFactory::getApplication()->setUserState($state, null);

        return $data;
    }

    protected function deleteItem($data)
    {
        $report = $this->getTable('Report');
        $report->load($data['id']);

        switch ($report->element) {
            case 'message':
                $table = JTable::getInstance('LoveFactoryMessage', 'Table');
                $table->load($report->reported_id);

                $table->deleted_by_receiver = 1;
                $table->deleted_by_sender = 1;

                $table->store();
                break;

            case 'profile':
                $table = JTable::getInstance('Profile', 'Table');
                $table->load($report->reported_id);

                $table->delete();
                break;

            case 'item_comment':
                $table = JTable::getInstance('ItemComment', 'Table');
                $table->delete($report->reported_id);
                break;

            case 'photo':
                $table = JTable::getInstance('Photo', 'Table');
                $table->delete($report->reported_id);
                break;

            case 'video':
                $table = JTable::getInstance('LoveFactoryVideo', 'Table');
                $table->delete($report->reported_id);
                break;

            case 'group_post':
                $table = JTable::getInstance('GroupPost', 'Table');
                $table->delete($report->reported_id);
                break;

            case 'group_thread':
                $table = JTable::getInstance('GroupThread', 'Table');
                $table->delete($report->reported_id);
                break;

            case 'group':
                $table = JTable::getInstance('Group', 'Table');
                $table->load($report->reported_id);
                $table->delete();
                break;
        }

        return true;
    }
}
