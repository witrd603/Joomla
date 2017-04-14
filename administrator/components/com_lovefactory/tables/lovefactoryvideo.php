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

class TableLoveFactoryVideo extends LoveFactoryTable
{
    public function __construct(&$db = null)
    {
        if (is_null($db)) {
            $db = JFactory::getDbo();
        }

        parent::__construct('#__lovefactory_videos', 'id', $db);
    }

    public function getThumbnailSource()
    {
        jimport('joomla.filesystem.file');

        // Initialise variables.
        $app = LoveFactoryApplication::getInstance();
        $filepath = $app->getUserFolder($this->user_id) . DS . $this->thumbnail;

        // Check if current thumbnail exists.
        if ('' == $this->thumbnail || !JFile::exists($filepath)) {
            return JURI::root() . 'components/com_lovefactory/assets/images/video_icon.png';
        }

        return $app->getUserFolder($this->user_id, true) . $this->thumbnail;
    }

    public function check()
    {
        if (!parent::check()) {
            return false;
        }

        // Check if title is set.
        if ('' == $this->title) {
            $this->setError(FactoryText::_('video_check_error_title_is_empty'));
            return false;
        }

        // Set the ordering.
        if (is_null($this->ordering)) {
            $this->ordering = self::getNextOrder($this->getDbo()->quoteName('user_id') . '=' . $this->getDbo()->quote($this->user_id));
        }

        if (is_null($this->date_added)) {
            $this->date_added = JFactory::getDate()->toSql();
        }

        // Process new uploaded thumbnail.
        if (!$this->uploadThumbnail($this->thumbnail)) {
            return false;
        }

        return true;
    }

    public function approve()
    {
        $this->approved = 1;

        if (!$this->store()) {
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryVideoApproved', array(
            'com_lovefactory.video_approved',
            $this,
        ));

        return true;
    }

    public function reject()
    {
        return $this->delete();
    }

    public function report()
    {
        $this->reported = 1;

        return $this->store();
    }

    public function delete($pk = null)
    {
        $k = $this->_tbl_key;
        $pk = (is_null($pk)) ? $this->$k : $pk;

        if (!parent::delete($pk)) {
            return false;
        }

        jimport('joomla.filesystem.file');

        $app = LoveFactoryApplication::getInstance();
        $src = $app->getUserFolder($this->user_id) . DS . $this->thumbnail;

        if (JFile::exists($src)) {
            JFile::delete($src);
        }

        // Delete item comments.
        $table = JTable::getInstance('ItemComment', 'Table');
        $table->deleteForItem($pk, 'video');

        JEventDispatcher::getInstance()->trigger('onLoveFactoryVideoRemoved', array(
            'com_lovefactory.video_removed',
            $this
        ));

        return true;
    }

    public function sendApprovalNotification()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // Check if video approvals are enabled and video is approved.
        if (!$settings->approval_videos || $this->approved) {
            return true;
        }

        // Send notifications.
        $mailer = FactoryMailer::getInstance();
        $mailer->sendAdminNotification(
            'item_pending_approval',
            array(
                'item_type' => 'video',
            ));
    }

    /**
     * Register video add event in the Activity Stream.
     * @return mixed
     */
//  public function registerActivity($created_at = null)
//  {
//    /* @var $activity TableActivity */
//    // Initialise variables.
//    $settings = LoveFactoryApplication::getInstance()->getSettings();
//    $activity = JTable::getInstance('Activity', 'Table');
//
//    // Check if video approvals are enabled and video is approved.
//    if ($settings->approval_videos && !$this->approved) {
//      return true;
//    }
//
//    return $activity->register(
//      'video_add',
//      $this->user_id,
//      $this->user_id,
//      $this->id,
//      array(),
//      $created_at
//    );
//  }

    /**
     * Returns the total number of videos the requested user has.
     *
     * @param null $userId
     * @return mixed
     */
    public function getCountForUser($userId = null, $statuses = array())
    {
        if (is_null($userId)) {
            $userId = JFactory::getUser();
        }

        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('COUNT(v.id)')
            ->from($dbo->quoteName($this->getTableName()) . ' v')
            ->where('v.user_id = ' . $dbo->quote($userId));

        if ($statuses) {
            $query->where('v.status IN (' . $dbo->q(implode(',', $statuses)) . ')');
        }

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    protected function uploadThumbnail($thumbnail)
    {
        if (is_array($thumbnail)) {
            return $this->uploadThumbnailPost($thumbnail);
        } elseif (is_string($thumbnail)) {
            return $this->uploadThumbnailFile($thumbnail);
        }

        return true;
    }

    protected function uploadThumbnailFile($thumbnail)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $thumbnail);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $contents = curl_exec($ch);

            curl_close($ch);

            $tmp_path = JFactory::getApplication()->get('tmp_path');
            $filename = basename($thumbnail);
            $thumbnail = $tmp_path . DS . $filename;

            JFile::write($thumbnail, $contents);
        }

        return $this->resizeImage($thumbnail);
    }

    protected function uploadThumbnailPost($thumbnail)
    {
        if (!is_array($thumbnail) || !isset($thumbnail['error']) || 4 == $thumbnail['error']) {
            return true;
        }

        if (0 != $thumbnail['error']) {
            $this->setError(FactoryText::sprintf('video_check_error_thumbnail_upload_error', $thumbnail['error']));
            return false;
        }

        // Check if file is an image.
        $size = getimagesize($thumbnail['tmp_name']);
        if (!$size) {
            $this->setError(FactoryText::_('video_check_error_thumbnail_not_image'));
            return false;
        }

        return $this->resizeImage($thumbnail['name'], $thumbnail['tmp_name']);
    }

    protected function resizeImage($image, $path = null)
    {
        if (is_null($path)) {
            $path = $image;
        }

        $app = LoveFactoryApplication::getInstance();
        $extension = strtolower(JFile::getExt($image));
        $filename = 'video_' . md5($this->user_id . time() . rand(0, 1000000)) . '.' . $extension;

        // Resize and move image.
        $filepath = JPath::clean($app->getUserFolder($this->user_id) . DS . $filename);
        require_once(JPATH_COMPONENT_SITE . DS . 'resizeIMG.php');

        $resize = new RESIZEIMAGE($path);
        $resize->resize_scale(100, 100, $filepath);
        $resize->close();

        $this->thumbnail = $filename;

        return true;
    }
}
