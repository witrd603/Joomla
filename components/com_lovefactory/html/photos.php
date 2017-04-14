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

class JHtmlLoveFactoryPhotos
{
    public static function privacyButton($status = 0)
    {
        $privacy = array(
            2 => 'private',
            0 => 'public',
            1 => 'friends',
        );

        return JHtml::_('LoveFactory.privacyButton', $privacy[$status]);
    }
}
