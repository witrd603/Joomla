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

class ActivityComment extends LoveFactoryActivity
{
    public function register(TableActivity $activity, array $params = array())
    {
        /** @var TableItemComment $comment */
        $comment = $params['comment'];

        return $activity->register(
            $comment->item_type . '_comment',
            $comment->user_id,
            $comment->item_user_id,
            $comment->item_id,
            array(
                'comment' => $comment->message,
            ),
            $comment->created_at
        );
    }
}
