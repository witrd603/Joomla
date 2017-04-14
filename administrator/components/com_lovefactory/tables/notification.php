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

class TableNotification extends JTable
{
    protected $params = array('groups');

    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_notifications', 'id', $db);
    }

    public function bind($src, $ignore = array())
    {
        foreach ($this->params as $param) {
            if (isset($src[$param]) && is_array($src[$param])) {
                $registry = new JRegistry($src[$param]);
                $src[$param] = $registry->toString();
            }
        }

        return parent::bind($src, $ignore);
    }
}
