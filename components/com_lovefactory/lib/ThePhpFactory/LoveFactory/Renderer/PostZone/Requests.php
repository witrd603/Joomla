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

class Requests
{
    protected $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function render($data)
    {
        $html = array();

        if ($data->request_message) {
            $html[] = '<div class="request_message">';

            if ($this->settings->approval_messages && !$data->approved) {
                $html[] = \FactoryText::_('requests_request_message_pending_approval');
            } else {
                $html[] = '<blockquote>' . nl2br($data->request_message) . '</blockquote>';
            }

            $html[] = '</div>';
        }

        $html[] = '<div class="actions">';

        if ($data->ismyrequest) {
            if (2 == $data->request_type) {
                $html[] = \JHtml::_('LoveFactory.RelationshipButton', $data->user_id);
            } else {
                $html[] = \JHtml::_('LoveFactory.FriendshipButton', $data->user_id);
            }
        } else {
            $controller = 'friend';
            if (2 == $data->request_type) {
                $html[] = '<i class="factory-icon icon-heart"></i>' . \FactoryText::_('requests_request_relationship');
                $controller = 'relationship';
            }

            $html[] = '<a href="' . \FactoryRoute::task($controller . '.accept&id=' . $data->user_id) . '"><i class="factory-icon icon-plus-circle"></i>' . \FactoryText::_('requests_request_accept') . '</a>';
            $html[] = '<a href="' . \FactoryRoute::task($controller . '.reject&id=' . $data->user_id) . '"><i class="factory-icon icon-minus-circle"></i>' . \FactoryText::_('requests_request_reject') . '</a>';
        }

        $html[] = \JHtml::_('LoveFactory.QuickMessage', $data->user_id);

        $html[] = '</div>';

        return implode('', $html);
    }
}
