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

JLoader::register('FactoryModel', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/methods.php');

class FrontendModelMaintenance extends FactoryModel
{
    public function perform()
    {
        // 1. Check for expired memberships.
        $this->checkExpiredMemberships();
    }

    private function checkExpiredMemberships()
    {
        // Select expired memberships.
        $results = $this->getUsersWithExpiredMemberships();

        // Check if any expired memberships were found.
        if (!$results || (count($results) == 1 && (is_null($results[0]) || !$results[0]))) {
            return true;
        }

        foreach ($results as $result) {
            if (!$result) {
                continue;
            }

            /** @var TableProfile $profile */
            $profile = JTable::getInstance('Profile', 'Table');
            $profile->load($result);

            /** @var BackendModelUserMembership $model */
            JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models');
            $model = JModelLegacy::getInstance('UserMembership', 'BackendModel');
            $model->expiredUpdate($profile);
        }

        return true;
    }

    private function getUsersWithExpiredMemberships()
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('DISTINCT p.user_id')
            ->from('#__lovefactory_profiles p')
            ->leftJoin('#__lovefactory_memberships_sold s ON s.id = p.membership_sold_id')
            ->where('p.membership_sold_id <> ' . $dbo->quote(0))
            ->where('s.expired = ' . $dbo->quote(0))
            ->where('s.end_membership <> ' . $dbo->quote($dbo->getNullDate()))
            ->where('s.end_membership < ' . $dbo->quote(JFactory::getDate()->toSql()));

        $results = $dbo->setQuery($query)
            ->loadObjectList('user_id');

        return array_keys($results);
    }
}
