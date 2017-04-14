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

namespace ThePhpFactory\LoveFactory\Security\Rules;

defined('_JEXEC') or die;

use ThePhpFactory\LoveFactory\Security\Exceptions\Redirect;
use ThePhpFactory\LoveFactory\Security\Rule;

abstract class Privacy extends Rule
{
    protected $parameter;

    public function authorize(\JUser $user)
    {
        $profileId = \JFactory::getApplication()->input->getInt('user_id', $user->id);
        $profile = \JTable::getInstance('Profile', 'Table');

        // Check if user is trying to access own profile.
        if ((int)$user->id === (int)$profileId) {
            return;
        }

        // Check if user profile was found.
        if (!$profile->load($profileId)) {
            $exception = new Redirect(
                \FactoryText::_('restriction_privacy_error_message')
            );
            throw $exception;
        }

        $settings = \JComponentHelper::getParams('com_lovefactory');
        $profile->setSettings($settings);

        $privacy = $profile->getParameter($this->parameter);

        switch ($privacy) {
            case 'public':
                break;

            case 'friends':
                \JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_lovefactory/models');

                $model = \JModelLegacy::getInstance('Friend', 'FrontendModel');
                $friends = $model->getFriendshipStatus($user->id, $profileId);

                if (1 !== (int)$friends) {
                    $exception = new Redirect(
                        \FactoryText::_('restriction_privacy_error_message')
                    );
                    throw $exception;
                }
                break;

            case 'private':
                if ((int)$user->id !== (int)$profileId) {
                    $exception = new Redirect(
                        \FactoryText::_('restriction_privacy_error_message')
                    );
                    throw $exception;
                }
                break;
        }
    }
}
