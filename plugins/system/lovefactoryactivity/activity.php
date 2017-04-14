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

abstract class LoveFactoryActivity
{
    protected $event = null;

    abstract public function register(TableActivity $table, array $params = array());

    public function remove(TableActivity $activity, $itemId, array $params = array())
    {
        if (null === $this->event) {
            return true;
        }

        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('a.id')
            ->from($dbo->qn($activity->getTableName(), 'a'))
            ->where('event = ' . $dbo->q($this->event))
            ->where('item_id = ' . $dbo->q($itemId));

        $results = $dbo->setQuery($query)
            ->loadAssocList();

        foreach ($results as $result) {
            $activity->delete($result['id']);
        }

        return true;
    }
}
