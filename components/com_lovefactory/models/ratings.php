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

class FrontendModelRatings extends FactoryModel
{
    public function getLatestRatingsForUser($userId = null, $limit = 5)
    {
        if (is_null($userId)) {
            $userId = JFactory::getUser()->id;
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('r.*, u.username, p.user_id AS valid_user, p.display_name')
            ->from('#__lovefactory_ratings r')
            ->leftJoin('#__lovefactory_profiles p ON p.user_id = r.sender_id')
            ->leftJoin('#__users u ON u.id = r.sender_id')
            ->where('r.receiver_id = ' . $dbo->quote($userId))
            ->order('r.date DESC');

        $results = $dbo->setQuery($query, 0, $limit)
            ->loadObjectList();

        return $results;
    }

    public function getMyRatingForUser($userId)
    {
        // Initialise variables.
        $user = JFactory::getUser();

        // Check if getting rating for own user.
        if ($userId == $user->id) {
            return false;
        }

        $table = JTable::getInstance('Rating', 'Table');
        if (!$table->load(array('sender_id' => $user->id, 'receiver_id' => $userId))) {
            return false;
        }

        return $table->rating;
    }
}
