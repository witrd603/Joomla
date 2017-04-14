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

class TableItemComment extends LoveFactoryTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_item_comments', 'id', $db);
    }

    public function check()
    {
        if (!parent::check()) {
            return false;
        }

        // Check if message is not empty.
        if ('' == trim($this->message)) {
            $this->setError(FactoryText::_('itemcomment_check_error_message_is_empty'));
            return false;
        }

        // Check if type comment is enabled.
        if ('profile' == $this->item_type && !LoveFactoryApplication::getInstance()->getSettings('enable_comments', 0)) {
            $this->setError(FactoryText::_('itemcomment_check_error_user_comments_not_enabled'));
            return false;
        }

        // If item is new, set the created date.
        if (is_null($this->created_at)) {
            $this->created_at = JFactory::getDate()->toSql();
        }

        return true;
    }

    public function report()
    {
        $this->reported = 1;

        if (!$this->store()) {
            return false;
        }

        return true;
    }

    public function deleteForItem($id, $type)
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->delete()
            ->from($dbo->quoteName($this->getTableName()))
            ->where('item_id = ' . $dbo->quote($id))
            ->where('item_type = ' . $dbo->quote($type));

        return $dbo->setQuery($query)
            ->execute();
    }

    public function approve()
    {
        $this->approved = 1;

        if (!$this->store()) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryCommentApproved', array(
            'com_lovefactory.comment_approved',
            $this
        ));

        return true;
    }

    public function reject()
    {
        return $this->delete();
    }

    public function getCommentsLink($type, $receiverUserId)
    {
        // Get token for token authentication.
        $dispatcher = JEventDispatcher::getInstance();
        $results = $dispatcher->trigger('FactoryTokenAuthCreateToken', array('parameters' => array('user_id' => $receiverUserId)));
        $token = $results ? '&' . $results[0] : '';

        switch ($type) {
            case 'profile':
                $link = FactoryRoute::view('comments' . $token, false, -1);
                break;

            case 'photo':
                $link = FactoryRoute::view('photo&id=' . $this->item_id . $token, false, -1);
                break;

            case 'video':
                $link = FactoryRoute::view('video&id=' . $this->item_id . $token, false, -1);
                break;

            default:
                $link = '';
                break;
        }

        return $link;
    }
}
