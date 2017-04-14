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

class FrontendModelPhotos extends FactoryModel
{
    public function getProfile($userId = null)
    {
        if (is_null($userId)) {
            $userId = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);
        }

        $model = JModelLegacy::getInstance('Profile', 'FrontendModel');
        return $model->getProfile($userId);
    }

    public function getItems($userId = null)
    {
        JTable::getInstance('Photo', 'Table');

        $dbo = $this->getDbo();
        $user = JFactory::getUser();

        if (is_null($userId)) {
            $userId = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);
        }

        $query = $dbo->getQuery(true)
            ->select('p.*')
            ->from('#__lovefactory_photos p')
            ->where('p.user_id = ' . $dbo->quote($userId))
            ->order('p.ordering ASC')
            ->group('p.id');

        // Select the number of comments.
        $query->select('COUNT(c.id) AS comments')
            ->leftJoin('#__lovefactory_item_comments c ON c.item_id = p.id AND c.item_type = ' . $dbo->quote('photo'));

        if (!$this->getIsMyGallery()) {
            $friendsModel = JModelLegacy::getInstance('Friend', 'FrontendModel');
            $status = $friendsModel->getFriendshipStatus($user->id, $userId);

            $conditionStatus = array();
            $conditionStatus[] = 'p.status = ' . $dbo->q(0);

            if (1 == $status) {
                $conditionStatus[] = 'p.status = ' . $dbo->q(1);
            }

            $query->where('(' . implode(' OR ', $conditionStatus) . ')');

            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('avatar_access');
            try {
                $restriction->isAllowed($user->id, $userId);
            } catch (Exception $e) {
                $query->leftJoin('#__lovefactory_profiles prf ON prf.user_id = ' . $dbo->q($userId) . ' AND prf.main_photo = p.id')
                    ->where('prf.main_photo IS NULL');
            }
        }

        $this->addFilterPrivacyCondition($query);
        $this->addQueryApprovalCondition($query);

        $results = $dbo->setQuery($query)
            ->loadObjectList('id', 'TablePhoto');

        return $results;
    }

    public function getFilterPrivacy()
    {
        $value = '';
        $filter = JFactory::getApplication()->input->get('filter', array(), 'array');

        if (isset($filter['privacy'])) {
            $value = $filter['privacy'];
        }

        return JHtml::_(
            'select.genericlist',
            array(
                '' => FactoryText::_('photos_privacy_all_photos'),
                'public' => FactoryText::_('photos_privacy_public'),
                'friends' => FactoryText::_('photos_privacy_friends'),
                'private' => FactoryText::_('photos_privacy_private'),
                'profile' => FactoryText::_('photos_privacy_profile'),),
            'filter[privacy]',
            '',
            '',
            '',
            $value,
            'filter_privacy'
        );
    }

    public function saveOrder($data)
    {
        if (!is_array($data) || !$data) {
            return false;
        }

        $userId = JFactory::getUser()->id;

        foreach ($data as $order => $photoId) {
            $table = $this->getTable('Photo');
            $table->load($photoId);

            if ($table->user_id != $userId) {
                return false;
            }

            $table->ordering = $order;

            if (!$table->store()) {
                return false;
            }
        }

        return true;
    }

    public function getIsMyGallery()
    {
        $user = JFactory::getUser();
        $user_id = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);

        return $user->id == $user_id;
    }

    public function getApproval()
    {
        return LoveFactoryApplication::getInstance()->getSettings('approval_photos', 0);
    }

    public function getUser()
    {
        $table = $this->getTable('Profile');
        $table->load(JFactory::getUser()->id);

        return $table;
    }

    public function getGravatar()
    {
        return LoveFactoryApplication::getInstance()->getSettings('enable_gravatar_integration', 0);
    }

    public function getRouteSetProfilePhoto()
    {
        return FactoryRoute::task('photo.setMain');
    }

    public function getTest()
    {
        return JFactory::getApplication()->input->getInt('test') ? '&test=1' : '';
    }

    protected function addFilterPrivacyCondition($query)
    {
        $value = '';
        $filter = JFactory::getApplication()->input->get('filter', array(), 'array');

        if (isset($filter['privacy'])) {
            $value = $filter['privacy'];
        }

        if (!$value) {
            return false;
        }

        if ('profile' == $value) {
            $query->leftJoin('#__lovefactory_profiles prf ON prf.user_id = p.user_id')
                ->where('prf.main_photo = p.id');
            return true;
        }

        $statuses = array(
            'public' => 0,
            'friends' => 1,
            'private' => 2,
        );

        if (!isset($statuses[$value])) {
            return false;
        }

        $query->where('p.status = ' . $query->quote($statuses[$value]));
    }

    protected function addQueryApprovalCondition($query, $showOwn = true)
    {
        if (!$this->getApproval()) {
            return false;
        }

        $condition = 'p.approved = ' . $query->quote(1);

        if ($showOwn) {
            $condition = '(' . $condition . ' OR p.user_id = ' . JFactory::getUser()->id . ')';
        }

        $query->where($condition);
    }
}
