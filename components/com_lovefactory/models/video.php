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

class FrontendModelVideo extends FactoryModel
{
    protected $item;

    public function save($data, $files)
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $table = $this->getTable('LoveFactoryVideo');

        // Check if user is allowed to add any more videos.
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('videos');

        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setState('membership_restriction_error', true);

            return false;
        }

        $data['title'] = strip_tags($data['title']);
        $data['description'] = strip_tags($data['description']);

        $tags = explode(',', LoveFactoryApplication::getInstance()->getSettings()->videos_embed_allowed_html);
        foreach ($tags as &$tag) {
            $tag = '<' . trim($tag) . '>';
        }
        $data['code'] = strip_tags($data['code'], implode('', $tags));

        // Save the video.
        if (!$table->save($data)) {
            $this->setError($table->getError());
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryVideoAdded', array(
            'com_lovefactory.video_added',
            $table,
        ));

        // Send admin approval notification.
        $table->sendApprovalNotification();

        return true;
    }

    public function getItem()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $table = $this->getTable('LoveFactoryVideo');
        $user = JFactory::getUser();

        if (!$id || !$table->load($id)) {
            throw new Exception(FactoryText::_('video_not_found'), 404);
        }

        if (!$this->isAllowedToView($user, $table)) {
            throw new Exception(FactoryText::_('video_not_available'), 403);
        }

        $table->username = JFactory::getUser($table->user_id)->username;

        $this->item = $table;

        return $this->item;
    }

    public function delete($batch)
    {
        // Initialise variables.
        $user = JFactory::getUser();
        if (!is_array($batch)) {
            $batch = array($batch);
        }

        foreach ($batch as $photoId) {
            $table = $this->getTable('LoveFactoryVideo');

            // Load the photo.
            $table->load($photoId);

            // Check if user is owner of the photo.
            if ($table->user_id != $user->id) {
                return false;
            }

            // Delete the photo.
            if (!$table->delete()) {
                return false;
            }

            $this->updateRemoved($photoId);
        }

        return true;
    }

    public function getViewItemComments()
    {
        JLoader::register('FrontendViewItemComments', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'views' . DS . 'itemcomments' . DS . 'view.html.php');

        $view = new FrontendViewItemComments();
        $model = JModelLegacy::getInstance('ItemComments', 'FrontendModel');

        $model->setItemType('Video');
        $model->setItemId($this->item->id);

        $view->setModel($model, true);

        return $view;
    }

    public function getNextId($next = true)
    {
        if (!$this->item) {
            return null;
        }

        $operand = $next ? '>' : '<';
        $order = $next ? 'ASC' : 'DESC';

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('v.id')
            ->from('#__lovefactory_videos v')
            ->where('v.user_id = ' . $dbo->quote($this->item->user_id))
            ->where('v.ordering ' . $operand . ' ' . $dbo->quote($this->item->ordering))
            ->order('v.ordering ' . $order);

        if (LoveFactoryApplication::getInstance()->getSettings('approval_videos', 0)) {
            $query->where('v.approved = ' . $dbo->quote(1));
        }

        $user = JFactory::getUser();
        if ($this->item->user_id != $user->id) {
            $query->leftJoin('#__lovefactory_friends f ON ((f.sender_id = ' . $dbo->quote($user->id) . ' AND f.receiver_id = v.user_id) OR (f.sender_id = v.user_id AND f.receiver_id = ' . $dbo->quote($user->id) . '))')
                ->where('(v.status = ' . $query->quote(0) . ' OR (v.status = ' . $dbo->quote(1) . ' AND v.id IS NOT NULL ))');
        }

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    public function getPrevId()
    {
        return $this->getNextId(false);
    }

    public function getYoutubeData($code)
    {
        $response = array(
            'status' => false,
            'error' => FactoryText::_('vide_get_youtube_data_error'),
        );

        if (!preg_match('/src=\"(.+)\"/U', $code, $matches)) {
            return $response;
        }

        $explode = explode('/', $matches[1]);
        $id = end($explode);

        $key = LoveFactoryApplication::getInstance()->getSettings('youtube_api_key');

        $client = new Google_Client();
        $client->setDeveloperKey($key);

        $youtube = new Google_Service_YouTube($client);

        try {
            $listResponse = $youtube->videos->listVideos('snippet', array(
                'id' => $id,
            ));

            if (!count($listResponse) || !isset($listResponse[0])) {
                return $response;
            }

            /** @var Google_Service_YouTube_VideoSnippet $snippet */
            $snippet = $listResponse[0]->getSnippet();

            $response['status'] = true;
            $response['title'] = $snippet->getTitle();
            $response['description'] = $snippet->getDescription();
            $response['thumbnail'] = $snippet->getThumbnails()->getDefault()->getUrl();

            unset($response['error']);
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

    public function getApprovalEnabled()
    {
        $approval = LoveFactoryApplication::getInstance()->getSettings('approval_videos', 0);

        return $approval;
    }

    protected function updateRemoved($photoId)
    {
        $removed = $this->getState('removed', array());
        $removed[] = $photoId;

        $this->setState('removed', $removed);

        return true;
    }

    protected function isAllowedToView($user, $table)
    {
        // Check if video approvals are enabled and if the video is approved.
        $approval = LoveFactoryApplication::getInstance()->getSettings('approval_videos', 0);
        if ($approval && !$table->approved) {
            return false;
        }

        // Check if user is photo owner or photo privacy is set to everyone.
        if (0 == $table->status || $table->user_id == $user->id) {
            return true;
        }

        // If photo privacy is set to friends, check if user are friends.
        $model = JModelLegacy::getInstance('Friend', 'FrontendModel');
        if (1 == $table->status && 1 == $model->getFriendshipStatus($table->user_id, $user->id)) {
            return true;
        }

        return false;
    }
}
