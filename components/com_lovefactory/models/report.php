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

jimport('joomla.application.component.model');

class FrontendModelReport extends FactoryModel
{
    public function send($data)
    {
        if (false !== strpos($data['type'], '.')) {
            list($data['element'], $data['type']) = explode('.', $data['type']);
        } else {
            $data['element'] = $data['type'];
            $data['type'] = '';
        }

        // Get reported item.
        $item = $this->getItem($data['element'], $data['id']);

        // Check if item exists.
        if (!$item) {
            return false;
        }

        // Check if item has already been reported.
        if (isset($item->reported) && $item->reported) {
            $this->setError(FactoryText::_('report_task_send_error_item_already_reported'));
            return false;
        }

        // Send report.
        $data['reporting_id'] = JFactory::getUser()->id;
        $data['comment'] = $data['message'];
        $data['reported_id'] = $data['id'];
        $data['user_id'] = $item->_user_id;

        unset($data['id']);

        $table = $this->getTable('Report');
        if (!$table->save($data)) {
            $this->setError($table->getError());
            return false;
        }

        // Mark item as reported.
        if (method_exists($item, 'report')) {
            $item->report();
        };

        // Trigger new report submitted event.
        JEventDispatcher::getInstance()->trigger('onLoveFactoryReportSubmitted', array(
            'com_lovefactory.report.submitted',
            $table,
        ));

        return true;
    }

    protected function getItem($element, $id)
    {
        $tables = array(
            'message' => array('table' => 'LoveFactoryMessage', 'user_id' => 'sender_id'),
            'profile' => array('table' => 'Profile', 'user_id' => 'user_id'),
            'comment' => array('table' => 'Comment', 'user_id' => 'sender_id'),
            'item_comment' => array('table' => 'ItemComment', 'user_id' => 'user_id'),
            'group' => array('table' => 'Group', 'user_id' => 'user_id'),
            'group_thread' => array('table' => 'GroupThread', 'user_id' => 'user_id'),
            'group_post' => array('table' => 'GroupPost', 'user_id' => 'user_id'),
            'photo' => array('table' => 'Photo', 'user_id' => 'user_id'),
            'video' => array('table' => 'LoveFactoryVideo', 'user_id' => 'user_id'),
        );

        $table = $this->getTable($tables[$element]['table']);

        if (!$id || !$table->load($id)) {
            $this->setError(FactoryText::_('report_task_send_error_item_not_found'));
            return false;
        }

        $table->_user_id = $table->{$tables[$element]['user_id']};

        return $table;
    }
}
