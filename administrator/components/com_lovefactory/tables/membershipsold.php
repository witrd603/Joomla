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

JLoader::register('LoveFactoryTable', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/methods.php');

class TableMembershipSold extends LoveFactoryTable
{
    var $id = null;
    var $user_id = null;
    var $membership_id = null;
    var $payment_id = null;
    var $start_membership = '0000-00-00 00:00:00';
    var $end_membership = '0000-00-00 00:00:00';
    var $expired = 0;
    var $months = null;
    var $title = null;
    var $default = null;
    var $trial = null;
    var $max_friends = null;
    var $max_photos = null;
    var $max_videos = null;
    var $max_messages_per_day = null;
    var $max_interactions_per_day = null;
    var $shoutbox = null;
    var $chatfactory = null;
    var $top_friends = null;
    var $groups_create = null;
    var $groups_join = null;
    var $same_gender_interaction = null;
    public $restrictions;

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_memberships_sold', 'id', $db);
    }

    public function markAsExpired($userId)
    {
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->update($dbo->qn($this->getTableName()))
            ->set('expired = ' . $dbo->q(1))
            ->where('expired = ' . $dbo->q(0))
            ->where('user_id = ' . $dbo->q($userId));

        $dbo->setQuery($query)
            ->execute();
    }

    public function markAsActive()
    {
        if (!$this->id) {
            return false;
        }

        $this->expired = 0;

        return parent::store();
    }

    public function createFromMembership($membership, $expiration_date, $user_id, $price_id = null, $trialId = null)
    {
        $membership = new JRegistry($membership);

        $this->bind($membership->toArray());

        $this->id = null;
        $this->user_id = $user_id;
        $this->membership_id = $membership->get('id');
        $this->start_membership = JFactory::getDate()->toSql();
        $this->end_membership = $membership->get('default') || !$expiration_date || $expiration_date < 0 ? JFactory::getDbo()->getNullDate() : JFactory::getDate($expiration_date)->toSql();

        if (!is_null($trialId)) {
            $this->trial = $trialId;
        }

        return true;
    }

    public function createFrom($userId, TableMembership $membership, JDate $expiration = null)
    {
        $date = JFactory::getDate();
        $dbo = JFactory::getDbo();

        $this->membership_id = $membership->id;
        $this->user_id = $userId;
        $this->title = $membership->title;
        $this->restrictions = $membership->restrictions;
        $this->start_membership = $date->toSql();
        $this->end_membership = null === $expiration ? $dbo->getNullDate() : $expiration->toSql();
    }

    public function isUnlimited()
    {
        return JFactory::getDbo()->getNullDate() == $this->end_membership;
    }
}
