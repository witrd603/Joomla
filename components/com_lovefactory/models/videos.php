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

class FrontendModelVideos extends FactoryModel
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
        JTable::getInstance('LoveFactoryVideo', 'Table');

        $dbo = $this->getDbo();
        $user = JFactory::getUser();
        if (is_null($userId)) {
            $userId = JFactory::getApplication()->input->getInt('user_id', JFactory::getUser()->id);
        }

        $query = $dbo->getQuery(true)
            ->select('v.*')
            ->from('#__lovefactory_videos v')
            ->where('v.user_id = ' . $dbo->quote($userId))
            ->order('v.ordering ASC')
            ->group('v.id');

        // Select the number of comments.
        $query->select('COUNT(c.id) AS comments')
            ->leftJoin('#__lovefactory_item_comments c ON c.item_id = v.id AND c.item_type = ' . $dbo->quote('video'));

        if (!$this->getIsMyGallery()) {
            $query->leftJoin('#__lovefactory_friends f ON ((f.sender_id = ' . $dbo->quote($user->id) . ' AND f.receiver_id = v.user_id) OR (f.sender_id = v.user_id AND f.receiver_id = ' . $dbo->quote($user->id) . ')) AND f.pending = ' . $dbo->quote(0))
                ->where('(v.status = ' . $dbo->quote(0) . ' OR (v.status = 1 AND f.id IS NOT NULL))');
        }

        $this->addFilterPrivacyCondition($query);
        $this->addQueryApprovalCondition($query);

        $results = $dbo->setQuery($query)
            ->loadObjectList('id', 'TableLoveFactoryVideo');

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
                '' => FactoryText::_('videos_privacy_all_videos'),
                'public' => FactoryText::_('videos_privacy_public'),
                'friends' => FactoryText::_('videos_privacy_friends'),
                'private' => FactoryText::_('videos_privacy_private'),),
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
            $table = $this->getTable('LoveFactoryVideo');
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
        return LoveFactoryApplication::getInstance()->getSettings('approval_videos', 0);
    }

    public function getRouteRetrieveYoutubeData()
    {
        return FactoryRoute::task('video.getyoutubedata');
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

        $statuses = array(
            'public' => 0,
            'friends' => 1,
            'private' => 2,
        );

        if (!isset($statuses[$value])) {
            return false;
        }

        $query->where('v.status = ' . $query->quote($statuses[$value]));
    }

    protected function addQueryApprovalCondition($query, $showOwn = true)
    {
        if (!$this->getApproval()) {
            return false;
        }

        $condition = 'v.approved = ' . $query->quote(1);

        if ($showOwn) {
            $condition = '(' . $condition . ' OR v.user_id = ' . JFactory::getUser()->id . ')';
        }

        $query->where($condition);
    }
}
