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

class FrontendModelMyMembership extends FactoryModel
{
    public function getItem()
    {
        JLoader::register('FrontendModelMemberships', JPATH_SITE . '/components/com_lovefactory/models/memberships.php');

        $user = JFactory::getUser();
        $model = JModelLegacy::getInstance('Memberships', 'FrontendModel');

        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($user->id);

        if ($profile->membership_sold_id) {
            $membership = JTable::getInstance('MembershipSold', 'Table');
            $membership->load($profile->membership_sold_id);
        } else {
            $membership = JTable::getInstance('Membership', 'Table');
            $membership->loadDefault();
        }

        $membership = $model->prepare($membership);

        return $membership[0];
    }

    public function getFeatures()
    {
        $model = JModelLegacy::getInstance('Memberships', 'FrontendModel');

        return $model->getFeatures();
    }

    public function getStatistics()
    {
        $statistics = JTable::getInstance('StatisticsPerDay', 'Table');
        $statistics->load(array('user_id' => JFactory::getUser()->id));

        return $statistics;
    }
}
