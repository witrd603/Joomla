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

class ActivityGroup extends LoveFactoryActivity
{
    protected $event = 'group_create';

    public function register(TableActivity $activity, array $params = array())
    {
        /** @var TableGroup $group */
        $group = $params['group'];

        return $activity->register(
            $this->event,
            $group->user_id,
            $group->user_id,
            $group->id,
            array(
                'title' => $group->title,
            ),
            $group->created_at
        );
    }
}
