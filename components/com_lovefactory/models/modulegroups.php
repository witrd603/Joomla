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

class FrontendModelModuleGroups extends FrontendModelModule
{
    public function getItems()
    {
        $dbo = $this->getDbo();
        $params = $this->getParams();

        $query = $dbo->getQuery(true)
            ->select('g.*')
            ->from('#__lovefactory_groups g');

        switch ($params->get('mode', 'members')) {
            case 'members':
            default:
                $query->select('COUNT(m.id) AS count')
                    ->leftJoin('#__lovefactory_group_members m ON m.group_id = g.id')
                    ->group('g.id')
                    ->having('COUNT(m.id) > 0')
                    ->order('count DESC');
                break;

            case 'posts':
                $query->select('COUNT(p.id) AS count')
                    ->leftJoin('#__lovefactory_group_posts p ON p.group_id = g.id')
                    ->group('g.id')
                    ->having('COUNT(p.id) > 0')
                    ->order('count DESC');
                break;
        }

        $results = $dbo->setQuery($query, 0, $params->get('limit', 5))
            ->loadObjectList();

        return $results;
    }
}
