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

class ActivityRating extends LoveFactoryActivity
{
    public function register(TableActivity $activity, array $params = array())
    {
        $rating = $params['rating'];
        $isNew = $params['isNew'];

        return $activity->register(
            'rating',
            $rating->sender_id,
            $rating->receiver_id,
            null,
            array(
                'rating' => $rating->rating,
                'isNew'  => $isNew,
            )
        );
    }
}
