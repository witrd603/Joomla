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

class LoveFactoryActivityHelper
{
    public function __construct()
    {
        JLoader::discover('Activity', __DIR__ . '/activities');
    }

    public function register($resource, array $params = array())
    {
        $table = $this->getTable();

        if (false === $activity = $this->buildActivity($resource)) {
            return false;
        }

        return $activity->register($table, $params);
    }

    public function remove($resource, $resourceId, array $params = array())
    {
        $table = $this->getTable();

        if (false === $activity = $this->buildActivity($resource)) {
            return false;
        }

        return $activity->remove($table, $resourceId, $params);
    }

    /**
     * @return TableActivity
     */
    private function getTable()
    {
        return JTable::getInstance('Activity', 'Table');
    }

    /**
     * @param $resource
     * @return LoveFactoryActivity
     */
    private function buildActivity($resource)
    {
        $explode = explode('.', $resource);
        $name = end($explode);
        $class = 'Activity' . str_replace('_', '', $name);

        if (!class_exists($class)) {
            return false;
        }

        return new $class;
    }
}
