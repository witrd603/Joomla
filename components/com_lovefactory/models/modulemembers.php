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

class FrontendModelModuleMembers extends FrontendModelModule
{
    protected $query;

    /**
     * Returns the options to display the members' informations.
     *
     * @return array
     */
    public function getOptions()
    {
        $options = array();
        $params = $this->getParams();

        if (!$params->get('show_username', 1)) {
            $options['hideUsername'] = true;
        }

        if ($params->get('link_target_blank', 0)) {
            $options['target'] = 'blank';
        }

        return $options;
    }

    public function getItems()
    {
        // Initialise variables.
        $dbo = JFactory::getDbo();
        $user = JFactory::getUser();

        // Select profiles
        $query = $dbo->getQuery(true)
            ->select('p.user_id, p.main_photo, p.sex');

        // Filter results.
        $this->addValidProfileConditions($query);
        $this->addGenderConditions($query);

        switch ($this->getParams()->get('mode')) {
            default:
            case 'latest':
                $query->select('p.date')
                    ->from('#__lovefactory_profiles p');

                $order = 'p.date DESC, p.user_id DESC';
                break;

            case 'rating':
                $query->select('p.rating')
                    ->from('#__lovefactory_profiles p')
                    ->where('p.rating > ' . $dbo->quote(0));

                $order = 'p.rating DESC';
                break;

            case 'random':
                $query->from('#__lovefactory_profiles p');

                $order = 'JDatabaseDriverPostgresql' == get_class($this->getDbo()) ? 'RANDOM()' : 'RAND()';
                break;

            case 'viewed':
                $query->select('v.user_id, MAX(v.date) AS date')
                    ->from('#__lovefactory_profile_visitors v')
                    ->leftJoin('#__lovefactory_profiles p ON p.user_id = v.user_id')
                    ->where('v.visitor_id = ' . $dbo->quote($user->id))
                    ->group('v.user_id, u.username, p.user_id');

                $order = 'date DESC';
                break;

            case 'visitors':
                $query->select('v.visitor_id, MAX(v.date) AS date')
                    ->from('#__lovefactory_profile_visitors v')
                    ->leftJoin('#__lovefactory_profiles p ON p.user_id = v.visitor_id')
                    ->where('v.user_id = ' . $dbo->quote($user->id))
                    ->group('v.visitor_id, u.username, p.user_id');

                $order = 'date DESC';
                break;

            case 'birthday':
                $field = 'p.' . $this->getParams()->get('field_birthdate', 'field_6');

                $query->select($field . ' AS date')
                    ->from('#__lovefactory_friends f')
                    ->leftJoin('#__lovefactory_profiles p ON p.user_id = (CASE WHEN f.sender_id = ' . $dbo->quote($user->id) . ' THEN f.receiver_id ELSE f.sender_id END)')
                    ->where('(f.sender_id = ' . $dbo->quote($user->id) . ' OR f.receiver_id = ' . $dbo->quote($user->id) . ')')
                    ->where('f.pending = ' . $dbo->quote(0));

                $dateNow = JFactory::getDate()->format('md');
                $dateLimit = JFactory::getDate('+1 month')->format('md');
                $month = JFactory::getDate()->format('m');

                $query
                    ->where('(CASE WHEN ' . $month . ' = 12 THEN SUBSTRING(' . $field . ', 5, 4) < ' . $query->quote($dateLimit) . ' ELSE SUBSTRING(p.field_6, 5, 4) > ' . $query->quote($dateNow) . ' END)')
                    ->where('(CASE WHEN ' . $month . ' = 12 THEN SUBSTRING(' . $field . ', 5, 4) < ' . $query->quote($dateLimit) . ' OR SUBSTRING(p.field_6, 5, 4) < ' . $query->quote('1231') . ' ELSE SUBSTRING(p.field_6, 5, 4) < ' . $query->quote($dateLimit) . ' END)');

                $order = 'SUBSTRING(' . $field . ', 5, 4) ASC';
                break;

            case 'visit':
                $query->select('p.lastvisit')
                    ->from('#__lovefactory_profiles p');

                $order = 'p.lastvisit DESC';
                break;
        }

        $query->leftJoin('#__users u ON u.id = p.user_id');
        $query->select('u.username AS joomla_username');

        $this->filterByProfilePhoto($query);

        // Filter results by membership.
        $this->addMembershipConditions($query);

        // Select member's username.
        $this->selectUsername($query);

        $query->order($order);

        $this->limit = $this->params->get('rows', 2) * $this->params->get('columns', 2);
        $dbo->setQuery($query, $this->getStart(), $this->limit);

        $result = $dbo->loadObjectList();

        if (!$result) {
            return false;
        }

        // Save query for pagination getTotal
        $this->query = $query;

        $items = array();
        foreach ($result as $item) {
            $profile = $this->getTable('Profile', 'Table');
            $profile->bind($item);

            if (!$item->username) {
                $item->username = $item->joomla_username;
            }

            $profile->username = $item->username;

            $items[] = $profile;
        }

        return $items;
    }

    public function getPagination()
    {
        jimport('joomla.html.pagination');

        $app = JFactory::getApplication();
        $router = $app->getRouter();

        $option     = $router->getVar('option');
        $controller = $router->getVar('controller');
        $task       = $router->getVar('task');
        $format     = $router->getVar('format');
        $view       = $router->getVar('view');

        $router->setVar('option', 'com_lovefactory');
        $router->setVar('controller', 'module');
        $router->setVar('task', 'pagination');
//        $router->setVar('format', 'raw');
        $router->setVar('view', '');

        $pagination = new JPagination($this->getTotal(), $this->getStart(), $this->limit);
        $pagination = $pagination->getPagesLinks();

        $router->setVar('option', $option);
        $router->setVar('controller', $controller);
        $router->setVar('task', $task);
        $router->setVar('format', $format ? $format : 'html');
        $router->setVar('view', $view);

        return $pagination;
    }

    protected function getTotal()
    {
        if (!$this->query) {
            return false;
        }

        $dbo = JFactory::getDbo();

        $this->query->setLimit(0, 0);

        $dbo->setQuery($this->query, 0, $this->limit * $this->params->get('pages', 1));
        $dbo->execute();
        
        return $dbo->getNumRows();
    }

    protected function getStart()
    {
        return JFactory::getApplication()->input->getInt('limitstart', 0);
    }

    public function getAvailableGenders()
    {
        $availableGenders = $this->params->get('filter_gender', array());
        $allGenders = $this->getAllGenders();

        if (!$availableGenders) {
            $availableGenders = $allGenders;
        } else {
            $keys = array_intersect(array_keys($allGenders), $availableGenders);
            $availableGenders = array_intersect_key($allGenders, $keys);
        }

        return $availableGenders;
    }

    public function getUserConfiguration()
    {
        $config = json_decode(JFactory::getApplication()->input->cookie->getString('lovefactory-module-' . $this->module->id, ''));

        if (!is_object($config)) {
            $config = (object)array();
        }

        if (!isset($config->gender)) {
            $config->gender = array();
        }

        if (!is_array($config->gender)) {
            $config->gender = array($config->gender);
        }

        return $config;
    }

    public function getUserFilterGenders()
    {
        return $this->params->get('user_filter_gender', 0);
    }

    protected function filterByProfilePhoto($query)
    {
        if (!$this->params->get('profile_photo_filter', 0)) {
            return false;
        }

        $query->where('p.main_photo <> 0');

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if ($settings->approval_photos) {
            $query->leftJoin('#__lovefactory_photos ph ON ph.id = p.main_photo')
                ->where('ph.approved = 1');
        }

        return true;
    }

    protected function getSelectedGenders()
    {
        $selectedGenders = array_keys($this->getAvailableGenders());

        if ($this->getUserFilterGenders()) {
            $userConfiguration = $this->getUserConfiguration();

            if ($userConfiguration->gender) {
                $selectedGenders = array_intersect($selectedGenders, $userConfiguration->gender);
            }
        }

        return $selectedGenders;
    }

    protected function getSelectedMemberships()
    {
        return $this->params->get('filter_membership', array());
    }

    /**
     * Returns an array of all defined genders in the component.
     *
     * @return array
     */
    protected function getAllGenders()
    {
        static $genders = null;

        if (is_null($genders)) {
            $table = JTable::getInstance('Field', 'Table');
            $table->load(array('type' => 'Gender'));

            $params = new JRegistry($table->params);

            // TODO FACTORY: Duplicate code in LoveFactoryField::getChoices()
            $language = JFactory::getLanguage();
            $genders = $params->get('choices.default', array());
            $translation = $params->get('choices.' . $language->getTag(), array());

            foreach ($genders as $key => $value) {
                if (isset($translation[$key])) {
                    $genders[$key] = $translation[$key];
                }
            }
        }

        return $genders;
    }

    /**
     * Filter query to select only valid members.
     *
     * @param $query
     * @return mixed
     */
    protected function addValidProfileConditions($query)
    {
        $query->where('p.online = 0')
//      ->where('p.validated = 1')
            ->where('u.block = 0')
            ->where('p.banned = ' . $query->quote(0));

        return $query;
    }

    /**
     * Filter query by selected genders.
     *
     * @param $query
     * @return mixed
     */
    protected function addGenderConditions($query)
    {
        $oppositeGenderFilter = false;
        $user = JFactory::getUser();

        // Check if we are displaying opposite genders.
        if (!$user->guest && $this->params->get('filter_opposite_gender', 0)) {
            $currentUser = JTable::getInstance('Profile', 'Table');
            $currentUser->load($user->id);

            $table = JTable::getInstance('Field', 'Table');
            $table->load(array('type' => 'Gender'));
            $params = new JRegistry($table->params);

            $oppositeGenders = $params->get('opposite');

            if (isset($oppositeGenders[$currentUser->sex])) {
                $query->where('p.sex = ' . $query->q($oppositeGenders[$currentUser->sex]));
                $oppositeGenderFilter = true;
            }
        }

        // Filter by gender
        if (!$oppositeGenderFilter) {
            $selectedGenders = $this->getSelectedGenders();
            $allGenders = $this->getAllGenders();

            if ($selectedGenders && $selectedGenders !== array_keys($allGenders)) {
                $query->where('p.sex IN (' . implode(',', $query->q($selectedGenders)) . ')');
            }
        }
    }

    /**
     * Filter query by selected membership.
     *
     * @param $query
     * @return mixed
     */
    protected function addMembershipConditions($query)
    {
        $selectedMemberships = $this->getSelectedMemberships();

        if ($selectedMemberships) {
            $query->leftJoin('#__lovefactory_memberships_sold m ON m.id = p.membership_sold_id')
                ->where('m.membership_id IN (' . implode(',', $selectedMemberships) . ')');
        }
    }

    /**
     * Query to select the username of the selected members.
     *
     * @param $query
     * @return mixed
     */
    protected function selectUsername($query)
    {
        $query->select('p.display_name AS username');

        return $query;
    }
}
