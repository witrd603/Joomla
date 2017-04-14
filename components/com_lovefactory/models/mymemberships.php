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

jimport('joomla.application.component.modellist');

class FrontendModelMyMemberships extends LoveFactoryFrontendModelList
{
    protected function getListQuery()
    {
        $query = parent::getListQuery();
        $user = JFactory::getUser();

        $query->select('m.*')
            ->from('#__lovefactory_memberships_sold m')
            ->where('m.user_id = ' . $query->quote($user->id))
            ->order('m.id DESC');

        return $query;
    }
}
