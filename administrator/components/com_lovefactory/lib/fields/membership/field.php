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

class LoveFactoryFieldMembership extends LoveFactoryField
{
    protected $accessPageWhiteList = array('profile_results', 'profile_view', 'friends_view', 'profile_map');

    public function renderInputView()
    {
        if (null === $this->data) {
            $table = JTable::getInstance('Membership', 'Table');
            $table->loadDefault();

            $this->data = $table->title;
        }

        return $this->data;
    }

    public function getId()
    {
        return 'membership_title';
    }

    public function getQueryView($query)
    {
        static $loaded = false;

        if ($loaded) {
            return true;
        }

        $loaded = true;

        $this->addQueryElement($query, 'join', '#__lovefactory_memberships_sold s ON s.id = p.membership_sold_id', 'leftjoin');
        $this->addQueryElement($query, 'join', '#__lovefactory_memberships m ON m.id = s.membership_id', 'leftjoin');
        $this->addQueryElement($query, 'select', 'm.title AS ' . $query->quoteName($this->getId()));
    }
}
