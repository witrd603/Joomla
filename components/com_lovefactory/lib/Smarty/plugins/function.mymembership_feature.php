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

function smarty_function_mymembership_feature($params, $template)
{
    $feature = $params['feature'];
    $features = $params['features'];
    $statistics = $params['statistics'];
    $date = JFactory::getDate()->format('Y-m-d');

    $html = array();

    switch ($feature) {
        case 'interactions':
            $html[] = $features[$feature];

            if ($features[$feature]) {
                $remaining = $date == $statistics->date_interactions
                    ? $features[$feature] - $statistics->interactions
                    : $features[$feature];
                $html[] = FactoryText::sprintf('mymembership_feature_interactions_remaining', $remaining);
            }
            break;

        case 'messages':
            $html[] = $features[$feature];

            if ($features[$feature]) {
                $remaining = $date == $statistics->date_messages
                    ? $features[$feature] - $statistics->messages
                    : $features[$feature];

                $html[] = FactoryText::sprintf('mymembership_feature_messages_remaining', $remaining);
            }
            break;

        case 'message_replies':
            $html[] = $features[$feature];

            if ($features[$feature]) {
                $remaining = $date == $statistics->date_message_replies
                    ? $features[$feature] - $statistics->message_replies
                    : $features[$feature];

                $html[] = FactoryText::sprintf('mymembership_feature_message_replies_remaining', $remaining);
            }
            break;

        default:
            $html[] = $features[$feature];
            break;
    }

    return implode("\n", $html);
}
