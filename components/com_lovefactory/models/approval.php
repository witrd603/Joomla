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

class FrontendModelApproval extends FactoryModel
{
    public function delete($data)
    {
        JArrayHelper::toInteger($data);
        $user = JFactory::getUser();

        if (!$data) {
            $this->setError(FactoryText::_('batch_no_item_selected'));
            return false;
        }

        foreach ($data as $item) {
            $table = $this->getTable('Approval', 'Table');

            // Check if item exists.
            if (!$item || !$table->load($item)) {
                $this->setError(FactoryText::_('approval_task_delete_error_item_not_found'));
                return false;
            }

            // Check if user is allowed to remove the item.
            if ($user->id != $table->user_id) {
                $this->setError(FactoryText::_('approval_task_delete_error_item_not_found'));
                return false;
            }

            if (!$table->delete()) {
                $this->setError($table->getError());
                return false;
            }
        }

        return true;
    }
}
