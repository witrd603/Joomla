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

class TableGateway extends JTable
{
    var $id = null;
    var $element = null;
    var $title = null;
    var $published = null;
    var $logo = null;
    var $ordering = null;
    var $params = null;

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_gateways', 'id', $db);
    }

    public function find($options = array())
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select($dbo->quoteName($this->getKeyName()))
            ->from($dbo->quoteName($this->getTableName()));

        foreach ($options as $col => $val) {
            $query->where($dbo->quoteName($col) . ' = ' . $dbo->quote($val));
        }

        return $dbo->setQuery($query)
            ->loadResult();
    }

    public function check()
    {
        if (empty($this->ordering)) {
            $this->ordering = self::getNextOrder();
        }

        return true;
    }

    public function bind($src, $ignore = array())
    {
        if (isset($src['params']) && is_array($src['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($src['params']);
            $src['params'] = (string)$registry;
        }

        return parent::bind($src, $ignore);
    }

    public function load($keys = null, $reset = true)
    {
        if (!parent::load($keys, $reset)) {
            return false;
        }

        // Convert params
        $this->params = new JRegistry($this->params);

        return true;
    }
}
