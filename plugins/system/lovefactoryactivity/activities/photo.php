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

class ActivityPhoto extends LoveFactoryActivity
{
    protected $event = 'photo_add';

    public function register(TableActivity $activity, array $params = array())
    {
        $photo = $params['photo'];

        return $activity->register(
            'photo_add',
            $photo->user_id,
            $photo->user_id,
            $photo->id,
            array(),
            $photo->date_added
        );
    }
}
