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

namespace ThePhpFactory\LoveFactory\Restrictions;

defined('_JEXEC') or die;

class RestrictionFactory
{
    private static $types = array(
        'photos',
        'videos',
        'friends',
        'friends_top',
        'same_gender_interaction',
        'messages',
        'message_replies',
        'interactions',
        'shoutbox',
        'groups_create',
        'groups_join',
        'profile_access',
        'avatar_access',
        'message_access',
        'comment_photo_access',
        'comment_video_access',
        'comment_profile_access',
        'blog_factory_access',
        'chat_factory_access',
    );
    private static $adjustableRestrictions = array(
        'photos', 'videos',
    );

    public static function buildRestriction($restriction)
    {
        if ('photos' === $restriction) {
            return new Type\Photos(\JFactory::getDbo());
        }

        if ('videos' === $restriction) {
            return new Type\Videos(\JFactory::getDbo());
        }

        if ('friends' === $restriction) {
            return new Type\Friends(\JFactory::getDbo());
        }

        if ('friends_top' === $restriction) {
            return new Type\FriendsTop(\JFactory::getDbo());
        }

        if ('same_gender_interaction' === $restriction) {
            return new Type\SameGenderInteraction(\JFactory::getDbo());
        }

        if ('messages' === $restriction) {
            return new Type\Messages(\JFactory::getDbo());
        }

        if ('message_replies' === $restriction) {
            return new Type\MessageReplies(\JFactory::getDbo());
        }

        if ('interactions' === $restriction) {
            return new Type\Interactions(\JFactory::getDbo());
        }

        if ('shoutbox' === $restriction) {
            return new Type\Shoutbox(\JFactory::getDbo());
        }

        if ('groups_create' === $restriction) {
            return new Type\GroupsCreate(\JFactory::getDbo());
        }

        if ('groups_join' === $restriction) {
            return new Type\GroupsJoin(\JFactory::getDbo());
        }

        if ('profile_access' === $restriction) {
            return new Type\ProfileAccess(\JFactory::getDbo());
        }

        if ('avatar_access' === $restriction) {
            return new Type\AvatarAccess(\JFactory::getDbo());
        }

        if ('message_access' === $restriction) {
            return new Type\MessageAccess(\JFactory::getDbo());
        }

        if ('comment_photo_access' === $restriction) {
            return new Type\CommentPhotoAccess(\JFactory::getDbo());
        }

        if ('comment_video_access' === $restriction) {
            return new Type\CommentVideoAccess(\JFactory::getDbo());
        }

        if ('comment_profile_access' === $restriction) {
            return new Type\CommentProfileAccess(\JFactory::getDbo());
        }

        if ('blog_factory_access' === $restriction) {
            return new Type\BlogFactoryAccess(\JFactory::getDbo());
        }

        if ('chat_factory_access' === $restriction) {
            return new Type\ChatFactoryAccess(\JFactory::getDbo());
        }

        return null;
    }

    public static function getTypes()
    {
        return self::$types;
    }

    public static function getAdjustableRestrictionTypes()
    {
        return self::$adjustableRestrictions;
    }
}
