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

class BackendModelGroupMember extends JModelLegacy
{
    function delete()
    {
        $group_id = JFactory::getApplication()->input->getInt('id');
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $member = $this->getTable('GroupMember');

        JArrayHelper::toInteger($cid);
        $this->group_id = $group_id;

        foreach ($cid as $member_id) {
            if (!$member->delete($member_id)) {
                return false;
            }
        }

        return true;
    }
}
