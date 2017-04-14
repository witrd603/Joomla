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

class plgSystemLoveFactoryActivity extends JPlugin
{
    private $application;
    /** @var LoveFactorySettings */
    private $settings;
    /** @var LoveFactoryActivityHelper */
    private $helper = null;

    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php');

        $this->application = JFactory::getApplication();
        $this->settings = LoveFactoryApplication::getInstance()->getSettings();
    }

    public function onLoveFactoryProfileStatusChanged($context, $userId, $status)
    {
        if ('com_lovefactory.profile_status_changed' !== $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if (!$this->settings->wallpage_add_status) {
            return true;
        }

        return $this->getHelper()->register('profile_status', array(
            'userId' => $userId,
            'status' => $status,
        ));
    }

    public function onLoveFactoryPhotoUploaded($context, $photo)
    {
        if ('com_lovefactory.photo_uploaded' !== $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if (!$this->settings->wallpage_add_photo) {
            return true;
        }

        if ($this->settings->approval_photos) {
            return true;
        }

        return $this->getHelper()->register('photo', array(
            'photo' => $photo,
        ));
    }

    public function onLoveFactoryPhotoApproved($context, $photo)
    {
        if ('com_lovefactory.photo_approved' !== $context) {
            return true;
        }

        if ($this->application->isSite()) {
            return true;
        }

        if (!$this->settings->wallpage_add_photo) {
            return true;
        }

        return $this->getHelper()->register('photo', array(
            'photo' => $photo,
        ));
    }

    public function onLoveFactoryPhotoRemoved($context, $photo)
    {
        if ('com_lovefactory.photo_removed' !== $context) {
            return true;
        }

        if (!$this->settings->wallpage_add_photo) {
            return true;
        }

        return $this->getHelper()->remove('photo', $photo->id);
    }

    public function onLoveFactoryRatingReceived($context, $rating, $isNew)
    {
        if ('com_lovefactory.rating_received' !== $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if (!$this->settings->wallpage_add_rating) {
            return true;
        }

        return $this->getHelper()->register('rating', array(
            'rating' => $rating,
            'isNew'  => $isNew,
        ));
    }

    public function onLoveFactoryCommentReceived($context, $table)
    {
        if ('com_lovefactory.comment_received' !== $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        switch ($table->item_type) {
            case 'profile':
                if (!$this->settings->wallpage_add_comment || $this->settings->approval_comments) {
                    return true;
                }
                break;

            case 'photo':
                if (!$this->settings->wallpage_add_photo_comment || $this->settings->approval_comments_photo) {
                    return true;
                }
                break;

            case 'video':
                if (!$this->settings->wallpage_add_video_comment || $this->settings->approval_comments_video) {
                    return true;
                }
                break;

            default:
                return true;
        }

        return $this->getHelper()->register('comment', array(
            'comment' => $table,
        ));
    }

    public function onLoveFactoryCommentApproved($context, $table)
    {
        if ('com_lovefactory.comment_approved' !== $context) {
            return true;
        }

        if ($this->application->isSite()) {
            return true;
        }

        switch ($table->item_type) {
            case 'profile':
                if (!$this->settings->wallpage_add_comment) {
                    return true;
                }
                break;

            case 'photo':
                if (!$this->settings->wallpage_add_photo_comment) {
                    return true;
                }
                break;

            case 'video':
                if (!$this->settings->wallpage_add_photo_comment) {
                    return true;
                }
                break;

            default:
                return true;
        }

        return $this->getHelper()->register('comment', array(
            'comment' => $table,
        ));
    }

    public function onLoveFactoryVideoAdded($context, $video)
    {
        if ('com_lovefactory.video_added' !== $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if (!$this->settings->wallpage_add_video) {
            return true;
        }

        if ($this->settings->approval_videos) {
            return true;
        }

        return $this->getHelper()->register('video', array(
            'video' => $video,
        ));
    }

    public function onLoveFactoryVideoApproved($context, $video)
    {
        if ('com_lovefactory.video_approved' !== $context) {
            return true;
        }

        if ($this->application->isSite()) {
            return true;
        }

        if (!$this->settings->wallpage_add_video) {
            return true;
        }

        return $this->getHelper()->register('video', array(
            'video' => $video,
        ));
    }

    public function onLoveFactoryVideoRemoved($context, $video)
    {
        if ('com_lovefactory.video_removed' !== $context) {
            return true;
        }

        if (!$this->settings->wallpage_add_video) {
            return true;
        }

        return $this->getHelper()->remove('video', $video->id);
    }

    public function onLoveFactoryFriendshipAccepted($context, $friendship, $friendshipType)
    {
        if ('com_lovefactory.friendship_accepted' !== $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        switch ($friendshipType) {
            case 'friend':
                if (!$this->settings->wallpage_add_friend) {
                    return true;
                }
                break;

            case 'relationship':
                if (!$this->settings->wallpage_add_relationship) {
                    return true;
                }
                break;

            default:
                return true;
        }

        return $this->getHelper()->register('friendship', array(
            'friendship'     => $friendship,
            'friendshipType' => $friendshipType,
        ));
    }

    public function onLoveFactoryFriendshipRemoved($context, $friendship, $friendshipType)
    {
        if ('com_lovefactory.friendship_removed' !== $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        switch ($friendshipType) {
            case 'friend':
                if (!$this->settings->wallpage_add_friend) {
                    return true;
                }
                break;

            case 'relationship':
                if (!$this->settings->wallpage_add_relationship) {
                    return true;
                }
                break;

            default:
                return true;
        }

        return $this->getHelper()->remove('friendship', $friendship->id);
    }

    public function onLoveFactoryGroupAfterSave($context, $table, $isNew)
    {
        if ('com_lovefactory.group' != $context) {
            return true;
        }

        if (!$isNew) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if (!$this->settings->wallpage_create_group) {
            return true;
        }

        if ($this->settings->approval_groups) {
            return true;
        }

        return $this->getHelper()->register('group', array(
            'group' => $table,
        ));
    }

    public function onLoveFactoryGroupApproved($context, $table)
    {
        if ('com_lovefactory.group_approved' != $context) {
            return true;
        }

        if ($this->application->isSite()) {
            return true;
        }

        if (!$this->settings->wallpage_create_group) {
            return true;
        }

        return $this->getHelper()->register('group', array(
            'group' => $table,
        ));
    }

    public function onLoveFactoryGroupRemoved($context, $table)
    {
        if ('com_lovefactory.group_removed' != $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if (!$this->settings->wallpage_create_group) {
            return true;
        }

        return $this->getHelper()->remove('group', $table->id);
    }

    public function onLoveFactoryUserJoinedGroup($context, $member, $group)
    {
        if ('com_lovefactory.user_joined_group' != $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if (!$this->settings->wallpage_join_group) {
            return true;
        }

        return $this->getHelper()->register('groupMember', array(
            'member' => $member,
            'group'  => $group,
        ));
    }

    public function onLoveFactoryMemberLeftGroup($context, $member)
    {
        if ('com_lovefactory.member_left_group' != $context) {
            return true;
        }

        if ($this->application->isAdmin()) {
            return true;
        }

        if (!$this->settings->wallpage_join_group) {
            return true;
        }

        return $this->getHelper()->remove('groupMember', $member->id, array(
            'member' => $member,
        ));
    }

    /**
     * @return LoveFactoryActivityHelper
     */
    private function getHelper()
    {
        if (null === $this->helper) {
            JLoader::register('LoveFactoryActivityHelper', __DIR__ . '/helper.php');
            JLoader::register('LoveFactoryActivity', __DIR__ . '/activity.php');
            $this->helper = new LoveFactoryActivityHelper();
        }

        return $this->helper;
    }
}
