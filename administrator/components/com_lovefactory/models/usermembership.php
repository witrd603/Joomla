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

class BackendModelUserMembership extends JModelLegacy
{
    const EXPIRATION_UNLIMITED = null;
    private $defaultMembership;

    public function manualUpdate(TableProfile $profile, $membershipId, $expiration)
    {
        /** @var TableMembership $membership */
        $membership = JTable::getInstance('Membership', 'Table');

        // Check if membership exists.
        if (!$membership->load($membershipId)) {
            return false;
        }

        $expiration = $this->filterExpirationDate($expiration);

        return $this->changeMembership($profile, $membership, $expiration);
    }

    public function expiredUpdate(TableProfile $profile)
    {
        $defaultMembership = $this->loadDefaultMembership();

        return $this->changeMembership($profile, $defaultMembership);
    }

    public function orderUpdate(TableProfile $profile, TableOrder $order)
    {
        $membership = $order->getMembership();
        $price = $order->getPrice();
        $expiration = $price->calculateExpirationDate();

        if (!$profile->hasDefaultMembership()) {
            $currentMembership = $profile->getMembership();

            // Check if user's current membership is the same as the new one.
            if ($currentMembership->membership_id == $membership->id) {
                // If current membership or new has unlimited interval, then the new expiration should
                // also be unlimited.
                if ($currentMembership->isUnlimited() || $price->isUnlimited()) {
                    $expiration = $price::UNLIMITED_EXPIRATION;
                } else {
                    // Else, extend the current interval with the new period.
                    $expiration = $price->calculateExpirationDate($currentMembership->end_membership);
                }
            }
        }

        return $this->changeMembership($profile, $membership, $expiration);
    }

    public function trialUpdate(TableProfile $profile, TableMembership $membership, JDate $expiration = null)
    {
        if (!$this->changeMembership($profile, $membership, $expiration)) {
            return false;
        }

        $profile->increaseTrials();

        return true;
    }

    public function freeUpdate(TableProfile $profile, TableMembership $membership, JDate $expiration = null)
    {
        return $this->changeMembership($profile, $membership, $expiration);
    }

    public function activateExpiredMembership($id)
    {
        $membership = JTable::getInstance('MembershipSold', 'Table');
        $membership->load($id);

        $membershipCurrent = JTable::getInstance('MembershipSold', 'Table');
        $membershipCurrent->load(array(
            'user_id' => $membership->user_id,
            'expired' => 0,
        ));

        if ($membershipCurrent->id) {
            $membershipCurrent->expired = 1;
            $membershipCurrent->store();
        }

        $membership->expired = 0;
        $membership->store();

        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($membership->user_id);
        $profile->membership_sold_id = $membership->id;
        $profile->store();

        $dispatcher = JEventDispatcher::getInstance();
        $dispatcher->trigger('onLoveFactoryUserMembershipChange', array(
            'com_lovefactory.user.membership_change', $profile, $membership,
        ));

        $this->setState('user_id', $membership->user_id);

        return true;
    }

    private function changeMembership(TableProfile $profile, TableMembership $membership, JDate $expiration = null)
    {
        // Mark old membership as expired.
        $this->markOldMembershipsAsExpired($profile->user_id);

        if ($membership->isDefault()) {
            $profile->membership_sold_id = 0;
        } else {
            /** @var TableMembershipSold $soldMembership */
            $soldMembership = $this->createSoldMembership($profile->user_id, $membership, $expiration);
            $profile->membership_sold_id = $soldMembership->id;
        }

        if (!$profile->store()) {
            return false;
        }

        // Trigger change membership event.
        $dispatcher = JEventDispatcher::getInstance();
        $dispatcher->trigger('onLoveFactoryUserMembershipChange', array(
            'com_lovefactory.user.membership_change', $profile, $membership,
        ));

        return true;
    }

    private function filterExpirationDate($expiration)
    {
        $nullDates = array('', null, JFactory::getDbo()->getNullDate());

        if (in_array($expiration, $nullDates, true)) {
            return self::EXPIRATION_UNLIMITED;
        }

        return JFactory::getDate($expiration);
    }

    private function loadDefaultMembership()
    {
        if (null === $this->defaultMembership) {
            $table = JTable::getInstance('Membership', 'Table');
            $table->load(array(
                'default' => 1,
            ));

            $this->defaultMembership = $table;
        }

        return $this->defaultMembership;
    }

    private function markOldMembershipsAsExpired($userId)
    {
        /** @var TableMembershipSold $membership */
        $membership = JTable::getInstance('MembershipSold', 'Table');
        $membership->markAsExpired($userId);
    }

    private function createSoldMembership($userId, TableMembership $membership, JDate $expiration = null)
    {
        /** @var TableMembershipSold $soldMembership */
        $soldMembership = JTable::getInstance('MembershipSold', 'Table');
        $soldMembership->createFrom($userId, $membership, $expiration);
        $soldMembership->store();

        return $soldMembership;
    }
}
