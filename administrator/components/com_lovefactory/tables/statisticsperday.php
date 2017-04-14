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

class TableStatisticsPerDay extends JTable
{
    var $id = null;
    var $user_id = null;
    var $date_messages = null;
    var $messages = null;
    var $date_interactions = null;
    var $interactions = null;

    function __construct(&$db)
    {
        parent::__construct('#__lovefactory_statistics_per_day', 'id', $db);
    }

    // Messages
    function getMessages($user_id)
    {
        $user = $this->find($user_id);

        if (is_null($user)) {
            return 0;
        }

        return ($user->date_messages == date('Y-m-d')) ? $user->messages : 0;
    }

    function updateMessages($user_id)
    {
        $user = $this->find($user_id);

        if (is_null($user)) {
            $this->date_messages = date('Y-m-d');
            $this->messages = 1;
            $this->user_id = $user_id;
        } else {
            $this->load($user->id);

            if ($this->date_messages == date('Y-m-d')) {
                $this->messages++;
            } else {
                $this->date_messages = date('Y-m-d');
                $this->messages = 1;
            }
        }

        $this->store();
    }

    // Message replies
    function getMessageReplies($user_id)
    {
        $user = $this->find($user_id);

        if (is_null($user)) {
            return 0;
        }

        return ($user->date_message_replies == date('Y-m-d')) ? $user->message_replies : 0;
    }

    function updateMessageReplies($user_id)
    {
        $user = $this->find($user_id);

        if (is_null($user)) {
            $this->date_message_replies = date('Y-m-d');
            $this->message_replies = 1;
            $this->user_id = $user_id;
        } else {
            $this->load($user->id);

            if ($this->date_message_replies == date('Y-m-d')) {
                $this->message_replies++;
            } else {
                $this->date_message_replies = date('Y-m-d');
                $this->message_replies = 1;
            }
        }

        $this->store();
    }

    // Interactions
    function getInteractions($user_id)
    {
        $user = $this->find($user_id);

        if (is_null($user)) {
            return 0;
        }

        return ($user->date_interactions == date('Y-m-d')) ? $user->interactions : 0;
    }

    function updateInteractions($user_id)
    {
        $user = $this->find($user_id);

        if (is_null($user)) {
            $this->date_interactions = date('Y-m-d');
            $this->interactions = 1;
            $this->user_id = $user_id;
        } else {
            $this->load($user->id);

            if ($this->date_interactions == date('Y-m-d')) {
                $this->interactions++;
            } else {
                $this->date_interactions = date('Y-m-d');
                $this->interactions = 1;
            }
        }

        $this->store();
    }

    // Helpers
    function find($user_id)
    {
        $query = ' SELECT s.*'
            . ' FROM #__lovefactory_statistics_per_day s'
            . ' WHERE s.user_id = ' . $user_id;
        $this->_db->setQuery($query);

        return $this->_db->loadObject();
    }
}
