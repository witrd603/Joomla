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

jimport('joomla.application.component.model');

class FrontendModelMessages extends FactoryModel
{
    var $_data;
    var $_total;
    var $_pagination;
    var $_limit;
    var $_limitstart;
    var $_inbox;

    function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');

        $this->_limit = JFactory::getApplication()->input->getInt('limit', $mainframe->get('list_limit'));
        $this->_limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);

        $this->_limitstart = ($this->_limit != 0 ? (floor($this->_limitstart / $this->_limit) * $this->_limit) : 0);
    }

    function getOutbox()
    {
        $this->_inbox = false;

        return $this->getData();
    }

    function getInbox()
    {
        $this->_inbox = true;

        return $this->getData();
    }

    function getData()
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->_limitstart, $this->_limit);
        }

        return $this->_data;
    }

    function _buildQuery()
    {
        $where = $this->_buildContentWhere();
        $leftJoin = $this->_buildContentLeftJoin();

        $query = ' SELECT m.*, u.username'
            . ' FROM #__lovefactory_messages m'
            . $leftJoin
            . $where
            . ' ORDER BY date DESC';

        return $query;
    }

    function _buildContentWhere()
    {
        $user = JFactory::getUser();
        $field = ($this->_inbox) ? 'receiver' : 'sender';

        $where = ' WHERE m.' . $field . '_id = ' . $user->id
            . ' AND deleted_by_' . $field . ' = 0';

        return $where;
    }

    function _buildContentLeftJoin()
    {
        $user = JFactory::getUser();
        $field = ($this->_inbox) ? 'sender' : 'receiver';

        $join = ' LEFT JOIN #__users u ON u.id = m.' . $field . '_id';

        return $join;
    }

    function getTotal()
    {
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    function getPagination()
    {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->_limitstart, $this->_limit);
        }

        return $this->_pagination;
    }
}
