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

class BackendModelDashboard extends JModelLegacy
{
    protected $limit = 5;

    public function __construct($config = array())
    {
        parent::__construct($config);

        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'settings.php');
    }

    public function getOrder()
    {
        $input = JFactory::getApplication()->input;
        $cookie = $input->cookie->get('lovefactory_dashboard_columns', '', 'string');

        $panels = array(
            'users',
            'latestusers',
            'latestreports',
            'latestpayments',
            'latestorders',
        );

        $array = array(
            'first' => array(
                'users',
            ),
            'second' => array(
                'latestusers',
                'latestreports',
                'latestorders',
                'latestpayments',
            ),
        );

        if (false === strpos($cookie, '/')) {
            return $array;
        }

        list($first, $second) = explode('/', $cookie);
        $first = trim($first, '.');
        $second = trim($second, '.');

        $first = '' == $first ? array() : explode('.', $first);
        $second = '' == $second ? array() : explode('.', $second);

        $merge = array_merge($first, $second);
        foreach ($panels as $panel) {
            if (!in_array($panel, $merge)) {
                $first[] = $panel;
            }
        }

        $array = array(
            'first' => $first,
            'second' => $second,
        );

        return $array;
    }

    public function getUsers()
    {
        $array = array();

        // Total
        $query = ' SELECT COUNT(user_id) AS count'
            . ' FROM #__lovefactory_profiles';
        $this->_db->setQuery($query);
        $users = $this->_db->loadObject();

        $array['total'] = $users ? $users->count : 0;

        // Banned
        $query = ' SELECT COUNT(user_id) AS count'
            . ' FROM #__lovefactory_profiles'
            . ' WHERE banned = 1';
        $this->_db->setQuery($query);
        $users = $this->_db->loadObject();

        $array['banned'] = $users ? $users->count : 0;

        return $array;
    }

    public function getLatestUsers()
    {
        $query = ' SELECT p.*, u.username'
            . ' FROM #__lovefactory_profiles p'
            . ' LEFT JOIN #__users u ON p.user_id = u.id'
            . ' ORDER BY date DESC';
        $this->_db->setQuery($query, 0, $this->limit);

        return $this->_db->loadObjectList();
    }

    public function getLatestPayments()
    {
        JLoader::register('TablePayment', JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables' . DS . 'payment.php');
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('p.*, u.username')
            ->from('#__lovefactory_payments p')
            ->leftJoin('#__users u ON u.id = p.user_id')
            ->order('p.received_at DESC');
        $results = $dbo->setQuery($query, 0, $this->limit)
            ->loadObjectList('', 'TablePayment');

        return $results;
    }

    public function getLatestReports()
    {
        $model = JModelLegacy::getInstance('Reports', 'BackendModel');
        $model->getState();
        $model->setState('list.limit', $this->limit);

        $results = $model->getItems();

        return $results;

        $types = array(1 => 'Message', 2 => 'Comment', 3 => 'Gallery', 4 => 'Photo Comment', 5 => JText::_('Video Comment'), 6 => JText::_('Group Post'));

        $query = ' SELECT r.*, u.username'
            . ' FROM #__lovefactory_reports r'
            . ' LEFT JOIN #__users u ON r.user_id = u.id'
            . ' ORDER BY date DESC'
            . ' LIMIT 0, ' . $this->limit;
        $results = $this->_db->setQuery($query)
            ->loadObjectList();

        foreach ($results as &$result) {
            $result->type = $types[$result->type_id];
        }

        return $results;
    }

    public function getLatestOrders()
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('o.*')
            ->from('#__lovefactory_orders AS o')
            ->order('o.created_at DESC');

        // Select gateway.
        $query->select('g.title AS gateway_title')
            ->leftJoin('#__lovefactory_gateways AS g ON g.id = o.gateway');

        $results = $dbo->setQuery($query, 0, $this->limit)
            ->loadObjectList();

        return $results;
    }

    public function getMemberships()
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('COUNT(p.user_id) AS count, m.title, m.id')
            ->from('#__lovefactory_memberships m')
            ->leftJoin('#__lovefactory_memberships_sold s ON s.membership_id = m.id')
            ->leftJoin('#__lovefactory_profiles p ON p.membership_sold_id = s.id')
            ->group('m.id')
            ->order('m.ordering ASC');

        $results = $dbo->setQuery($query)
            ->loadObjectList('id');

        $membership = JTable::getInstance('Membership', 'Table');
        $membership->loadDefault();

        $freeMemberships = $this->countFreeMemberships();

        $results[$membership->id]->count += $freeMemberships;

        return $results;
    }

    public function countFreeMemberships()
    {
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->select('COUNT(1)')
            ->from($dbo->qn('#__lovefactory_profiles', 'p'))
            ->where('p.membership_sold_id = ' . $dbo->q(0));

        return $dbo->setQuery($query)
            ->loadResult();
    }
}
