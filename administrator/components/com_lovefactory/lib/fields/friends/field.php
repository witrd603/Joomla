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

class LoveFactoryFieldFriends extends LoveFactoryField
{
    protected $accessPageBlackList = array('registration', 'profile_edit', 'profile_fillin');

    public function renderInputView()
    {
        $url = FactoryRoute::view('friends&user_id=' . $this->userId);

        if (JFactory::getApplication()->isAdmin()) {
            $url = str_replace('/administrator', '', $url);
        }

        return '<a href="' . $url . '">' . $this->data . '</a>';
    }

    public function getId()
    {
        return 'friends';
    }

    public function addQueryView($query)
    {
        $query->select('(SELECT COUNT(ft.id) FROM #__lovefactory_friends ft WHERE (ft.sender_id = p.user_id OR ft.receiver_id = p.user_id) AND ft.pending = 0)  AS friends');

//    $this->addQueryElement($query, 'select', 'COUNT(DISTINCT ft.id) AS friends');
//    $this->addQueryElement($query, 'join', '#__lovefactory_friends ft ON (ft.sender_id = ' . $query->quoteName('p.user_id') . ' OR ft.receiver_id = ' . $query->quoteName('p.user_id') . ') AND ft.pending = ' . $query->quote(0) . ' AND ft.type = ' . $query->quote(1), 'leftjoin');
    }
}
