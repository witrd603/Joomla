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

namespace ThePhpFactory\LoveFactory\Renderer\PostZone;

defined('_JEXEC') or die;

class MyFriends
{
    public function render($data)
    {
        $html = array();

        $html[] = '<div class="actions">';
        $html[] = \JHtml::_('LoveFactory.QuickMessage', $data->user_id);
        $html[] = \JHtml::_('LoveFactory.TopFriendButton', $data->user_id, $data->is_top_friend);
        $html[] = \JHtml::_('LoveFactory.FriendshipButton', $data->user_id);
        $html[] = '</div>';

        return implode('', $html);
    }
}
