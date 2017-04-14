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

jimport('joomla.application.component.modeladmin');

class BackendModelUser extends JModelAdmin
{
    protected $option = 'com_lovefactory';

    public function getForm($data = array(), $loadData = true)
    {
        /* @var $form JForm */
        // Get the form.
        $form = $this->loadForm(
            $this->option . '.' . $this->getName(),
            $this->getName(),
            array(
                'control' => 'jform',
                'load_data' => $loadData,
            ));

        if (empty($form)) {
            return false;
        }

        // Set the labels and descriptions in case they are not set.
        foreach ($form->getFieldsets() as $fieldset) {
            foreach ($form->getFieldset($fieldset->name) as $field) {
                $label = $form->getFieldAttribute($field->fieldname, 'label', '', $field->group);
                $desc = $form->getFieldAttribute($field->fieldname, 'description', '', $field->group);
                $base = 'form_field_user_' . $field->fieldname;

                if ('' == $label) {
                    $label = FactoryText::_($base . '_label');
                    $form->setFieldAttribute($field->fieldname, 'label', $label, $field->group);
                }

                if ('' == $desc) {
                    $desc = FactoryText::_($base . '_desc');
                    $form->setFieldAttribute($field->fieldname, 'description', $desc, $field->group);
                }
            }
        }

        return $form;
    }

    public function getTable($name = 'Profile', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    public function getItem($pk = null)
    {
        static $item = null;

        if (is_null($item)) {
            $user_id = (int)$this->getState($this->getName() . '.id');
            $user = JFactory::getUser();

            $dbo = $this->getDbo();
            $query = $dbo->getQuery(true)
                ->select('p.*')
                ->from('#__lovefactory_profiles p')
                ->where('p.user_id = ' . $dbo->quote($user_id));

            // Select the username.
            $query->select('u.username')
                ->leftJoin('#__users u ON u.id = p.user_id');

            $query->select('f.id AS is_friend')
                ->leftJoin('#__lovefactory_friends f ON ((f.sender_id = p.user_id AND f.receiver_id = ' . $dbo->quote($user->id) . ') OR (f.receiver_id = p.user_id AND f.sender_id = ' . $dbo->quote($user->id) . ')) AND f.pending = ' . $dbo->quote(0));

            if ('viewable' == JFactory::getApplication()->input->getCmd('mode', 'viewable')) {
                $page = 'profile_view';
                $mode = 'view';
            } else {
                $page = 'profile_edit';
                $mode = 'edit';
            }

            $page = LoveFactoryPage::getInstance(
                $page,
                $mode,
                array('isAdmin' => true)
            );

            foreach ($page->getFields(false) as $field) {
                $field->addQueryView($query);
            }

            $item = $dbo->setQuery($query)
                ->loadObject();
        }

        return $item;
    }

    public function getToken()
    {
        $table = $this->getTable('AdminProfileToken', 'Table');

        return $table->generateForUser($this->getItem()->user_id);
    }

    public function getIps()
    {
        $item = $this->getItem();
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->select('i.*, COUNT(c.ip) AS shares')
            ->from('#__lovefactory_ips i')
            ->leftJoin('#__lovefactory_ips c ON c.ip = i.ip AND c.user_id <> i.user_id')
            ->where('i.user_id = ' . $dbo->quote($item->user_id))
            ->group('i.ip, i.id')
            ->order('i.visits DESC');
        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return $results;
    }

    public function getPhotos()
    {
        $item = $this->getItem();
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->select('p.*')
            ->from('#__lovefactory_photos p')
            ->where('p.user_id = ' . $dbo->quote($item->user_id));

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        foreach ($results as $i => $result) {
            $table = $this->getTable('Photo');
            $table->bind($result);
            $results[$i] = $table;
        }

        return $results;
    }

    public function getVideos()
    {
        $item = $this->getItem();
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->select('v.*')
            ->from('#__lovefactory_videos v')
            ->where('v.user_id = ' . $dbo->quote($item->user_id));

        $results = $dbo->setQuery($query)
            ->loadObjectList('id');

        $array = array();

        foreach ($results as $id => $result) {
            $table = $this->getTable('Video', 'TableLoveFactory');
            $table->bind($result);

            $array[$id] = $table;
        }

        return $array;
    }

    public function getMemberships()
    {
        $item = $this->getItem();
        $dbo = $this->getDbo();
        $membership = JTable::getInstance('MembershipSold', 'Table');

        $query = $dbo->getQuery(true)
            ->select('m.id, m.title, m.expired, m.start_membership, m.end_membership, m.trial')
            ->from($dbo->qn($membership->getTableName(), 'm'))
            ->where($dbo->qn('m.user_id') . ' = ' . $dbo->q($item->user_id))
            ->order($dbo->qn('m.start_membership') . ' DESC');

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return $results;
    }

    public function deleteVideos($userId, $videos)
    {
        if (!is_array($videos) || !$videos) {
            return false;
        }

        foreach ($videos as $video) {
            $table = $this->getTable('LoveFactoryVideo');
            $table->load($video);

            if ($table->user_id == $userId) {
                $table->delete();
            }
        }
    }

    public function deletePhotos($userId, $photos)
    {
        if (!is_array($photos) || !$photos) {
            return false;
        }

        foreach ($photos as $photo) {

            $table = $this->getTable('Photo');
            $table->load($photo);

            if ($table->user_id == $userId) {
                if ($table->delete()) {
                    $dbo = $this->getDbo();
                    $query = $dbo->getQuery(true)
                        ->update('#__lovefactory_profiles')
                        ->set('main_photo = ' . $dbo->quote(0))
                        ->where('main_photo = ' . $dbo->quote($table->id));

                    $dbo->setQuery($query)
                        ->execute();
                }
            }
        }

        return true;
    }

    public function save($data)
    {
        if (!parent::save($data)) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryBackendProfileSave', array(
            'com_lovefactory.backend.profile.save.after', $data['user_id'], $data
        ));

        return true;
    }

    public function ban($users, $value)
    {
        JArrayHelper::toInteger($users);

        if (!$users) {
            return true;
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_profiles')
            ->set('banned = ' . $dbo->quote($value))
            ->where('user_id IN (' . implode(',', $users) . ')');

        $result = $dbo->setQuery($query)
            ->execute();

        return $result;
    }

    public function updateDisplayName($nameFieldId, $surnameFieldId)
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select($dbo->qn('p.user_id'))
            ->select($dbo->qn('u.username'))
            ->from($dbo->qn('#__lovefactory_profiles', 'p'))
            ->leftJoin($dbo->qn('#__users', 'u') . ' ON ' . $dbo->qn('u.id') . ' = ' . $dbo->qn('p.user_id'));

        if ($nameFieldId) {
            $query->select($dbo->qn('p.field_' . $nameFieldId));
        }
        if ($surnameFieldId) {
            $query->select($dbo->qn('p.field_' . $surnameFieldId));
        }

        $profiles = $dbo->setQuery($query)
            ->loadObjectList();

        $dispatcher = JEventDispatcher::getInstance();

        foreach ($profiles as $profile) {
            $table = JTable::getInstance('Profile', 'Table');
            $table->bind($profile);

            $dispatcher->trigger('onLoveFactoryProfileBeforeSave', array(
                'com_lovefactory.settings.save.before',
                $table,
                $nameFieldId,
                $surnameFieldId,
                $profile->username,
            ));

            $table->store();
        }

        return true;
    }

    protected function loadFormData()
    {
        $context = $this->option . '.edit.' . $this->getName() . '.data';

        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($context, array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        JFactory::getApplication()->setUserState($context, null);

        return $data;
    }

    public function clearFilled(array $ids = array())
    {
        if (!$ids) {
            throw new Exception('You must select at least one user!');
        }

        $table = $this->getTable();
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->select($dbo->qn('p.user_id'))
            ->from($dbo->qn($table->getTableName(), 'p'))
            ->where($dbo->qn('filled') . ' = ' . $dbo->q(1))
            ->where($dbo->qn('user_id') . ' IN (' . implode(',', $dbo->q($ids)) . ')');
        $results = $dbo->setQuery($query)
            ->loadAssocList('user_id');

        $results = array_keys($results);

        if (!$results) {
            throw new Exception('You must select at least one user that has their profile filled!');
        }

        $query = $dbo->getQuery(true)
            ->update($dbo->qn($table->getTableName()))
            ->set($dbo->qn('filled') . ' = ' . $dbo->q(0))
            ->where($dbo->qn('user_id') . ' IN (' . implode(',', $dbo->q($results)) . ')');

        $dbo->setQuery($query)
            ->execute();

        JEventDispatcher::getInstance()->trigger('onAfterClearedFilledProfiles', array(
            'com_lovefactory',
            $results,
        ));
    }

    public function markFilled(array $ids = array())
    {
        if (!$ids) {
            throw new Exception('You must select at least one user!');
        }

        $table = $this->getTable();
        $dbo = $this->getDbo();

        $query = $dbo->getQuery(true)
            ->select($dbo->qn('p.user_id'))
            ->from($dbo->qn($table->getTableName(), 'p'))
            ->where($dbo->qn('filled') . ' = ' . $dbo->q(0))
            ->where($dbo->qn('user_id') . ' IN (' . implode(',', $dbo->q($ids)) . ')');
        $results = $dbo->setQuery($query)
            ->loadAssocList('user_id');

        $results = array_keys($results);

        if (!$results) {
            throw new Exception('You must select at least one user that does not have their profile filled!');
        }

        $query = $dbo->getQuery(true)
            ->update($dbo->qn($table->getTableName()))
            ->set($dbo->qn('filled') . ' = ' . $dbo->q(1))
            ->where($dbo->qn('user_id') . ' IN (' . implode(',', $dbo->q($results)) . ')');

        $dbo->setQuery($query)
            ->execute();

        JEventDispatcher::getInstance()->trigger('onAfterMarkedFilledProfiles', array(
            'com_lovefactory',
            $results,
        ));
    }
}
