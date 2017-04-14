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

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

class plgSystemLoveFactoryNotifications extends JPlugin
{
    protected $mailer = null;
    /** @var LoveFactorySettings */
    protected $settings;
    /** @var JApplicationCms */
    protected $application;

    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php');

        $this->settings = LoveFactoryApplication::getInstance()->getSettings();
        $this->application = JFactory::getApplication();
    }

    public function onLoveFactoryOrderCreate($context, $order)
    {
        if ('com_lovefactory.order.create' != $context) {
            return false;
        }

        // Get gateway
        $gateway = JTable::getInstance('Gateway', 'Table');
        $gateway->load($order->gateway);

        // Send notification.
        $result = $this->getMailer()->sendAdminNotification('new_order', array(
            'order_username' => JFactory::getUser($order->user_id)->username,
            'order_title'    => $order->title,
            'order_amount'   => $order->amount,
            'order_currency' => $order->currency,
            'order_gateway'  => $gateway->title,
            'order_date'     => JHtml::_('date', JFactory::getDate($order->created_at), 'Y-m-d H:i:s'),
        ));

        return $result;
    }

    public function onLoveFactoryPaymentReceived($context, $payment)
    {
        if ('com_lovefactory.payment.received' != $context) {
            return false;
        }

        JFactory::getLanguage()->load('com_lovefactory', JPATH_ADMINISTRATOR);

        $gateway = JTable::getInstance('Gateway', 'Table');
        $gateway->load($payment->gateway);

        // Send the notification.
        $result = $this->getMailer()->sendAdminNotification('new_payment', array(
            'payment_gateway'  => $gateway->title,
            'payment_amount'   => $payment->amount,
            'payment_currency' => $payment->currency,
            'payment_status'   => $payment->getStatusLabel($payment->status),
            'payment_date'     => $payment->payment_date,
        ));

        return $result;
    }

    public function onLoveFactoryReportSubmitted($context, $report)
    {
        if ('com_lovefactory.report.submitted' != $context) {
            return false;
        }

        JFactory::getLanguage()->load('com_lovefactory', JPATH_ADMINISTRATOR);

        // Send the notification.
        $result = $this->getMailer()->sendAdminNotification('new_report', array(
            'report_type'    => FactoryText::_('report_type_' . $report->element . ('' == $report->type ? '' : '_' . $report->type)),
            'report_date'    => JHtml::_('date', $report->date, 'Y-m-d H:i:s'),
            'report_message' => trim($report->comment),
        ));

        return $result;
    }

    public function onLoveFactoryProfileFillinReminder($context, $userId, $username)
    {
        if ('com_lovefactory.fillin.reminder' != $context) {
            return false;
        }

        // Send the notification.
        $result = $this->getMailer()->send('profile_fillin_reminder', $userId, array(
            'receiver_username' => $username,
        ), true, true);

        return $result;
    }

    public function onLoveFactoryUserMembershipChange($context, $profile, $membership)
    {
        if ('com_lovefactory.user.membership_change' != $context) {
            return true;
        }

        $result = $this->getMailer()->send('membership_changed', $profile->user_id, array(
            'receiver_username'     => JFactory::getUser($profile->user_id)->username,
            'receiver_display_name' => $profile->display_name,
            'membership_title'      => $membership->title,
        ));

        return $result;
    }

    public function onLoveFactoryFriendshipRequestSent($context, $friendship)
    {
        if ('com_lovefactory.friendship_request.after' !== $context) {
            return true;
        }

        $senderUsername = JFactory::getUser($friendship->sender_id)->username;
        $receiverUsername = JFactory::getUser($friendship->receiver_id)->username;

        $senderProfile = JTable::getInstance('Profile', 'Table');
        $senderProfile->load($friendship->sender_id);

        $receiverProfile = JTable::getInstance('Profile', 'Table');
        $receiverProfile->load($friendship->receiver_id);

        $result = $this->getMailer()->send('friend_request', $friendship->receiver_id, array(
            'receiver_username'     => $receiverUsername,
            'sender_username'       => $senderUsername,
            'message'               => $friendship->message,
            'receiver_display_name' => $receiverProfile->display_name,
            'sender_display_name'   => $senderProfile->display_name,
        ));

        return $result;
    }

    public function onLoveFactoryRelationshipRequestSent($context, $relationship)
    {
        if ('com_lovefactory.relationship_request.after' !== $context) {
            return true;
        }

        $senderUsername = JFactory::getUser($relationship->sender_id)->username;
        $receiverUsername = JFactory::getUser($relationship->receiver_id)->username;

        $senderProfile = JTable::getInstance('Profile', 'Table');
        $senderProfile->load($relationship->sender_id);

        $receiverProfile = JTable::getInstance('Profile', 'Table');
        $receiverProfile->load($relationship->receiver_id);

        $result = $this->getMailer()->send('relationship_request', $relationship->receiver_id, array(
            'receiver_username'     => $receiverUsername,
            'sender_username'       => $senderUsername,
            'message'               => $relationship->message,
            'receiver_display_name' => $receiverProfile->display_name,
            'sender_display_name'   => $senderProfile->display_name,
        ));

        return $result;
    }

    public function onLoveFactoryInteractionSent($context, $interaction, $token)
    {
        if ('com_lovefactory.interaction_sent.after' !== $context) {
            return true;
        }

        $senderUsername = JFactory::getUser($interaction->sender_id)->username;
        $receiverUsername = JFactory::getUser($interaction->receiver_id)->username;

        $senderProfile = JTable::getInstance('Profile', 'Table');
        $senderProfile->load($interaction->sender_id);

        $receiverProfile = JTable::getInstance('Profile', 'Table');
        $receiverProfile->load($interaction->receiver_id);

        $result = $this->getMailer()->send('interaction_received', $interaction->receiver_id, array(
            'receiver_username'     => $receiverUsername,
            'sender_username'       => $senderUsername,
            'interactions_link'     => FactoryRoute::view('interactions' . $token, false, -1),
            'receiver_display_name' => $receiverProfile->display_name,
            'sender_display_name'   => $senderProfile->display_name,
        ));

        return $result;
    }

    public function onLoveFactoryMessageSent($context, $message)
    {
        if ('com_lovefactory.message_sent' !== $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if ($this->settings->approval_messages) {
            return $this->itemPendingApprovalNotification('message');
        }

        return $this->messageSentNotification($message);
    }

    public function onLoveFactoryMessageApproved($context, $message)
    {
        if ('com_lovefactory.message_approved' !== $context) {
            return true;
        }

        if ($this->application->isSite()) {
            return true;
        }

        $this->messageSentNotification($message);

        return true;
    }

    public function onLoveFactoryCommentReceived($context, $comment)
    {
        if ('com_lovefactory.comment_received' !== $context) {
            return true;
        }

        if ($comment->item_id == $comment->user_id) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        switch ($comment->item_type) {
            case 'profile':
                if ($this->settings->approval_comments) {
                    return $this->itemPendingApprovalNotification('profile comment');
                }
                break;

            case 'photo':
                if ($this->settings->approval_comments_photo) {
                    return $this->itemPendingApprovalNotification('photo comment');
                }
                break;

            case 'video':
                if ($this->settings->approval_comments_video) {
                    return $this->itemPendingApprovalNotification('video comment');
                }
                break;

            default:
                return true;
        }

        return $this->commentReceivedNotification($comment);
    }

    public function onLoveFactoryCommentApproved($context, $comment)
    {
        if ('com_lovefactory.comment_approved' !== $context) {
            return true;
        }

        if ($comment->item_id == $comment->user_id) {
            return true;
        }

        if ($this->application->isSite()) {
            return true;
        }

        return $this->commentReceivedNotification($comment);
    }

    public function onLoveFactoryRatingReceived($context, $rating, $isNew)
    {
        if ('com_lovefactory.rating_received' !== $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        return $this->ratingReceivedNotification($rating);
    }

    public function onLoveFactoryRegistration($context, $user, $data, $userActivation)
    {
        if ('com_lovefactory.registration' !== $context) {
            return true;
        }

        $this->signupNotification($user, $data, $userActivation);
        $this->signupAdministratorNotification($data);

        return true;
    }

    public function onLoveFactoryPhotoUploaded($context, $photo)
    {
        if ('com_lovefactory.photo_uploaded' !== $context) {
            return false;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if (!$this->settings->approval_photos) {
            return true;
        }

        return $this->itemPendingApprovalNotification('photo');
    }

    protected function getMailer()
    {
        if (null === $this->mailer) {
            if (!class_exists('FactoryMailer')) {
                JLoader::register('FactoryMailer', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/methods.php');
            }

            $this->mailer = FactoryMailer::getInstance();
        }

        return $this->mailer;
    }

    protected function itemPendingApprovalNotification($type)
    {
        return $this->getMailer()->sendAdminNotification(
            'item_pending_approval',
            array(
                'item_type' => $type,
            ));
    }

    protected function messageSentNotification($message)
    {
        $dispatcher = JEventDispatcher::getInstance();
        $results = $dispatcher->trigger('FactoryTokenAuthCreateToken', array('parameters' => array('user_id' => $message->receiver_id)));
        $token = $results ? '&' . $results[0] : '';

        $receiver = JTable::getInstance('Profile', 'Table');
        $sender = JTable::getInstance('Profile', 'Table');

        $receiver->load($message->receiver_id);
        $sender->load($message->sender_id);

        // Send the notification.
        $result = $this->getMailer()->send(
            'message_received',
            $message->receiver_id,
            array(
                'receiver_username'     => JFactory::getUser($message->receiver_id)->username,
                'sender_username'       => JFactory::getUser($message->sender_id)->username,
                'message_body'          => nl2br($message->text),
                'messages_link'         => FactoryRoute::view('inbox' . $token, false, -1),
                'receiver_display_name' => $receiver->display_name,
                'sender_display_name'   => $sender->display_name,
            )
        );

        return $result;
    }

    protected function commentReceivedNotification($comment)
    {
        $types = array(
            'profile' => 'comment_received',
            'photo'   => 'comment_photo_received',
            'video'   => 'comment_video_received',
        );

        $profile = JTable::getInstance('Profile', 'Table');
        $sender = JTable::getInstance('Profile', 'Table');

        $profile->load($comment->item_user_id);
        $sender->load($comment->user_id);

        return $this->getMailer()->send(
            $types[$comment->item_type],
            $comment->item_user_id,
            array(
                'receiver_username'     => JFactory::getUser($comment->item_user_id)->username,
                'receiver_display_name' => $profile->display_name,
                'sender_username'       => JFactory::getUser($comment->user_id)->username,
                'sender_display_name'   => $sender->display_name,
                'message'               => nl2br($comment->message),
                'comments_link'         => $comment->getCommentsLink($comment->item_type, $comment->item_user_id),
            ));
    }

    protected function ratingReceivedNotification($rating)
    {
        $dispatcher = JEventDispatcher::getInstance();
        $results = $dispatcher->trigger('FactoryTokenAuthCreateToken', array('parameters' => array('user_id' => $rating->receiver_id)));
        $token = $results ? '&' . $results[0] : '';

        $sender = JTable::getInstance('Profile', 'Table');
        $sender->load($rating->sender_id);

        $receiver = JTable::getInstance('Profile', 'Table');
        $receiver->load($rating->receiver_id);

        // Send notification
        return $this->getMailer()->send(
            'rating_received',
            $rating->receiver_id,
            array(
                'receiver_username'     => JFactory::getUser($rating->receiver_id)->username,
                'sender_username'       => JFactory::getUser($rating->sender_id)->username,
                'rating'                => $rating->rating,
                'profile_link'          => FactoryRoute::view('profile' . $token, false, -1),
                'receiver_display_name' => $receiver->display_name,
                'sender_display_name'   => $sender->display_name,
            )
        );
    }

    protected function signupNotification($user, $data, $userActivation)
    {
        switch ($userActivation) {
            // Self activation.
            case 1:
                return $this->getMailer()->send(
                    'signup_with_user_activation',
                    $user->id,
                    array(
                        'name'            => $data['name'],
                        'site_name'       => $data['sitename'],
                        'site_url'        => $data['siteurl'],
                        'activation_link' => $data['siteurl'] . 'index.php?option=com_users&task=registration.activate&token=' . $data['activation'],
                        'username'        => $data['username'],
                        'password'        => $data['password_clear'],
                    ), true, true
                );

            // Admin activation.
            case 2:
                return $this->getMailer()->send(
                    'signup_with_admin_activation',
                    $user->id,
                    array(
                        'name'            => $data['name'],
                        'site_name'       => $data['sitename'],
                        'site_url'        => $data['siteurl'],
                        'activation_link' => $data['siteurl'] . 'index.php?option=com_users&task=registration.activate&token=' . $data['activation'],
                        'username'        => $data['username'],
                        'password'        => $data['password_clear'],
                    ), true, true
                );

            // No activation.
            case 0:
                return $this->getMailer()->send(
                    'signup_without_activation',
                    $user->id,
                    array(
                        'name'      => $data['name'],
                        'site_name' => $data['sitename'],
                        'site_url'  => $data['siteurl'],
                    ), true, true
                );
        }

        return true;
    }

    protected function signupAdministratorNotification($data)
    {
        $params = JComponentHelper::getParams('com_users');

        if (!$params->get('useractivation') < 2 || !$params->get('mail_to_admin') == 1) {
            return true;
        }

        // Get all admin users.
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('u.id, u.name, u.email, u.sendEmail')
            ->from('#__users u')
            ->where('u.sendEmail = ' . $dbo->quote(1));
        $results = $dbo->setQuery($query)
            ->loadObjectList();

        // Send notifications.
        foreach ($results as $result) {
            $this->getMailer()->send(
                'signup_admin_notification',
                $result->id,
                array(
                    'receiver_username'   => JFactory::getUser($result->id)->username,
                    'registered_username' => $data['username'],
                    'site_name'           => $data['sitename'],
                    'site_url'            => $data['siteurl'],
                ), true, true
            );
        }

        return true;
    }
}
