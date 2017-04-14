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

class NotFilled extends Rule
{
    public function authorize(\JUser $user)
    {
        /** @var \TableProfile $profile */

        $profile = \JTable::getInstance('Profile', 'Table');
        $profile->load($user->id);

        if ($profile->isFilled()) {
            $exception = new Redirect(
                \FactoryText::_('restriction_notfilled_error_message')
            );
            $exception->setRedirect(\JRoute::_('index.php?option=com_lovefactory&view=profile', false));

            throw $exception;
        }
    }
}
