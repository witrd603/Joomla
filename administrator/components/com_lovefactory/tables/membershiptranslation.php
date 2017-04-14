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

class TableMembershipTranslation extends JTable
{
    var $id = null;
    var $membership_id = null;
    var $lang = null;
    var $title = null;

    function __construct(&$db)
    {
        parent::__construct('#__lovefactory_memberships_translation', 'id', $db);
    }

    function find($options = array())
    {
        $dbo = JFactory::getDBO();
        $where = array();

        foreach ($options as $col => $val) {
            $where[] = $col . ' = ' . $dbo->Quote($val);
        }

        $query = ' SELECT t.*'
            . ' FROM #__lovefactory_memberships_translation t'
            . ' WHERE ' . implode(' AND ', $where);
        $dbo->setQuery($query);
        $result = $dbo->loadObject();

        $this->bind($result);

        if (!$result) {
            $this->membership_id = $options['membership_id'];
            $this->lang = $options['lang'];
        }

        return $this;
    }
}
