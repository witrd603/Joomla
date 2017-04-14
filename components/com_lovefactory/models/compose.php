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

class FrontendModelCompose extends FactoryModel
{
    public function getCounters()
    {
        $model = JModelLegacy::getInstance('Inbox', 'FrontendModel');

        return $model->getCounters();
    }

    public function getReceiver()
    {
        $reply = $this->getReply();

        if ($reply) {
            $receiverId = $reply->isSent() ? $reply->receiver_id : $reply->sender_id;

        } else {
            $receiverId = JFactory::getApplication()->input->getString('receiver', '');
        }

        $user = $this->getTable('User', 'JTable');

        if (!$receiverId || !$user->load($receiverId)) {
            return null;
        }

        $profile = $this->getTable('Profile', 'Table');
        $profile->load($receiverId);

        return array(
            'id' => $user->username,
            'name' => $profile->display_name ? $profile->display_name : $user->username,
        );
    }

    public function getReply()
    {
        static $reply = null;

        if (is_null($reply)) {
            $replyId = JFactory::getApplication()->input->getInt('reply_id', 0);
            $table = $this->getTable('LovefactoryMessage');
            $user = JFactory::getUser();
            $reply = false;

            if ($replyId && $table->load($replyId)) {
                if ($table->sender_id == $user->id || $table->receiver_id == $user->id) {
                    $reply = $table;
                }
            }
        }

        return $reply;
    }

    public function getTitle()
    {
        $reply = $this->getReply();

        if (!$reply) {
            return '';
        }

        $title = $reply->title ? $reply->title : FactoryText::_('messages_no_subject');

        return FactoryText::sprintf('compose_reply_message_title', $title);
    }

    public function getText()
    {
        $reply = $this->getReply();

        if (!$reply) {
            return '';
        }

        return FactoryText::sprintf('compose_reply_message_text', $reply->text);
    }

    public function getData()
    {
        $session = JFactory::getSession();

        $data = $session->get('com_lovefactory.message.compose', array(
            'title' => $this->getTitle(),
            'text' => $this->getText(),
            'reply_id' => $this->getReplyId(),
        ));

        $session->set('com_lovefactory.message.compose', null);

        return $data;
    }

    public function getReplyId()
    {
        return JFactory::getApplication()->input->getInt('reply_id');
    }

    public function getForm()
    {
        $file = JPATH_SITE . '/components/com_lovefactory/models/forms/message.xml';
        $form = JForm::getInstance('com_lovefactory.message', $file, array('control' => 'message'));

        LoveFactoryHelper::addFormLabels($form);

        $data = $this->getData();
        $form->bind($data);

        return $form;
    }
}
