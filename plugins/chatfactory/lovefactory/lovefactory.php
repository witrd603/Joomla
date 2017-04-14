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

class PlgChatFactoryLoveFactory extends JPlugin
{
    private $isIntegrated = null;
    /** @var LoveFactorySettings */
    private $settings = null;

    public function onFriendsQuery($context, $query, $userId, $tableAlias = 'f')
    {
        if ('com_chatfactory' !== $context) {
            return false;
        }

        if (!$this->isIntegrated()) {
            return false;
        }

        $query->leftJoin(
            $query->qn('#__lovefactory_friends', $tableAlias) .
            ' ON ' . $tableAlias . '.pending = ' . $query->q(0) .
            ' AND ((f.sender_id = ' . $query->q($userId) . ' AND f.receiver_id = u.user_id) OR (f.receiver_id = ' . $query->q($userId) . ' AND f.sender_id = u.user_id))'
        );

        return true;
    }

    public function onBlockedQuery($context, $query, $userId, $tableAlias = 'b')
    {
        if ('com_chatfactory' !== $context) {
            return false;
        }

        if (!$this->isIntegrated()) {
            return false;
        }

        $query->leftJoin(
            $query->qn('#__lovefactory_blacklist', $tableAlias) .
            ' ON b.sender_id = ' . $query->q($userId) . ' AND b.receiver_id = u.user_id '
        );

        return true;
    }

    public function onNicknameQuery($context, $query, $column = 'nickname')
    {
        if ('com_chatfactory' !== $context) {
            return false;
        }

        if (!$this->isIntegrated()) {
            return false;
        }

        $settings = $this->getSettings();

        if (!isset($settings->display_user_name) || (!$settings->display_user_name[0] && !$settings->display_user_name[1])) {
            $query->select('ju.username AS ' . $column)
                ->leftJoin('#__users ju ON ju.id = u.user_id');
        } else {
            $query->select('lu.display_name AS ' . $column)
                ->leftJoin('#__lovefactory_profiles lu ON lu.user_id = u.user_id');
        }

        return true;
    }

    public function onNicknameColumn($context)
    {
        if ('com_chatfactory' !== $context) {
            return false;
        }

        if (!$this->isIntegrated()) {
            return false;
        }

        $settings = $this->getSettings();

        if (!isset($settings->display_user_name) || (!$settings->display_user_name[0] && !$settings->display_user_name[1])) {
            return 'ju.username';
        } else {
            return 'lu.display_name';
        }

        return false;
    }

    public function onRestrictionsQuery($context, $query, $user)
    {
        if ('com_chatfactory' !== $context) {
            return false;
        }

        if (!$this->isIntegrated()) {
            return false;
        }

        $this->addShowOnlyFriendsQuery($query);
        $this->addDisplayOnlyOppositeGenderQuery($query, $user);
        $this->addSameGenderInteractionQuery($query, $user);

        return true;
    }

    public function onChatFactoryInit($context, $user)
    {
        if ('com_chatfactory' !== $context) {
            return true;
        }

        if (!$this->isIntegrated()) {
            return true;
        }

        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('chat_factory_access');

        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function onAvatarSourceProvider($context)
    {
        if ('com_chatfactory' !== $context) {
            return false;
        }

        if (!$this->isIntegrated()) {
            return false;
        }

        return array(
            'value' => 'lovefactory',
            'text'  => 'Love Factory',
        );
    }

    public function onAvatarSource($context, $source, $userId)
    {
        if ('com_chatfactory' !== $context) {
            return false;
        }

        if ('lovefactory' !== $source) {
            return false;
        }

        if (!$this->isIntegrated()) {
            return false;
        }

        /** @var TableProfile $table */
        $table = JTable::getInstance('Profile', 'Table');
        $table->load($userId);

        return $table->getProfilePhotoSource(true);
    }

    private function isIntegrated()
    {
        if (null === $this->isIntegrated) {
            $this->isIntegrated = false;

            $extension = JTable::getInstance('Extension');
            $result = $extension->find(array(
                'element' => 'com_lovefactory',
                'type'    => 'component',
            ));

            if ($result) {
                JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php');
                $settings = $this->getSettings();

                if (isset($settings->enable_chatfactory_integration) && $settings->enable_chatfactory_integration) {
                    $this->isIntegrated = true;

                    require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';
                }
            }
        }

        return $this->isIntegrated;
    }

    private function getSettings()
    {
        if (null === $this->settings) {
            $this->settings = LoveFactoryApplication::getInstance()->getSettings();
        }

        return $this->settings;
    }

    private function addShowOnlyFriendsQuery(JDatabaseQuery $query)
    {
        // Check if we only show friends.
        if (0 == $this->getSettings()->chatfactory_integration_users_list) {
            $query->where('f.id IS NOT NULL');
        }
    }

    private function addDisplayOnlyOppositeGenderQuery(JDatabaseQuery $query, JUser $user)
    {
        if (1 == $this->getSettings()->opposite_gender_display) {
            $helper = new ThePhpFactory\LoveFactory\Helper\OppositeGender($this->getSettings());
            $helper->addOppositeGenderSearchCondition($query, $user, 'love_profile_opposite');

            $query->leftJoin(
                $query->qn('#__lovefactory_profiles', 'love_profile_opposite')
                . ' ON love_profile_opposite.user_id = u.user_id'
            );
        }
    }

    private function addSameGenderInteractionQuery(JDatabaseQuery $query, JUser $user)
    {
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('same_gender_interaction');
        $isRestricted = !(boolean)$restriction->getCurrentMembershipRestriction($user->id);

        if ($isRestricted) {
            $profile = JTable::getInstance('Profile', 'Table');
            $profile->load($user->id);

            $query->where('love_profile_membership_restriction.sex <> ' . $query->q($profile->sex))
                ->leftJoin(
                    $query->qn('#__lovefactory_profiles', 'love_profile_membership_restriction')
                    . ' ON love_profile_membership_restriction.user_id = u.user_id'
                );
        }
    }
}
