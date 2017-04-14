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

class FrontendViewMessage extends FactoryView
{
    protected
        $get = array('item', 'conversation');

    protected function postDisplay()
    {
        $user = JFactory::getUser();

        if ($user->id === $this->item->receiver_id) {
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('message_access');

            try {
                $restriction->isAllowed($user->id);
            } catch (Exception $e) {
                JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
                JFactory::getApplication()->redirect(FactoryRoute::view('memberships'));
            }
        }
    }
}
