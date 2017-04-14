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

class ActivityVideo extends LoveFactoryActivity
{
    protected $event = 'video_add';

    public function register(TableActivity $activity, array $params = array())
    {
        $video = $params['video'];

        return $activity->register(
            'video_add',
            $video->user_id,
            $video->user_id,
            $video->id,
            array(),
            $video->date_added
        );
    }
}
