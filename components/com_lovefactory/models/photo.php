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

class FrontendModelPhoto extends FactoryModel
{
    protected $item;

    public function getItem()
    {
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $table = $this->getTable('Photo');
        $user = JFactory::getUser();

        if (!$table->load($id)) {
            throw new Exception(FactoryText::_('photo_not_found'), 404);
        }

        if (!$this->isAllowedToView($user, $table)) {
            throw new Exception(FactoryText::_('photo_not_available'), 403);
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
            $table = $this->getTable('Photo');

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

            $this->removeMainPhotoFromProfiles($table->id);
            $this->updateRemoved($photoId);
        }

        return true;
    }

    public function getViewItemComments()
    {
        JLoader::register('FrontendViewItemComments', LoveFactoryApplication::getInstance()->getPath('component_site') . DS . 'views' . DS . 'itemcomments' . DS . 'view.html.php');

        $view = new FrontendViewItemComments();
        $model = JModelLegacy::getInstance('ItemComments', 'FrontendModel');

        $model->setItemType('Photo');
        if ($this->item) {
            $model->setItemId($this->item->id);
        }

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
            ->select('p.id')
            ->from('#__lovefactory_photos p')
            ->where('p.user_id = ' . $dbo->quote($this->item->user_id))
            ->where('p.ordering ' . $operand . ' ' . $dbo->quote($this->item->ordering))
            ->order('p.ordering ' . $order);

        if (LoveFactoryApplication::getInstance()->getSettings('approval_photos', 0)) {
            $query->where('p.approved = ' . $dbo->quote(1));
        }

        $user = JFactory::getUser();
        if ($this->item->user_id != $user->id) {
            $query->leftJoin('#__lovefactory_friends f ON ((f.sender_id = ' . $dbo->quote($user->id) . ' AND f.receiver_id = p.user_id) OR (f.sender_id = p.user_id AND f.receiver_id = ' . $dbo->quote($user->id) . '))')
                ->where('(p.status = ' . $query->quote(0) . ' OR (p.status = ' . $dbo->quote(1) . ' AND f.id IS NOT NULL ))');
        }

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }

    public function getPrevId()
    {
        return $this->getNextId(false);
    }

    public function upload($batch, $privacy)
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $app = LoveFactoryApplication::getInstance();
        $settings = $app->getSettings();
        $photos = array();

        // Check if any file was uploaded.
        if (!is_array($batch) || !$batch) {
            $this->setError(FactoryText::_('photo_task_upload_error_no_files_uploaded'));
            return false;
        }

        $valid = true;
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('photos');

        foreach ($batch as $i => $item) {
            // Check if user is allowed to add any more photos.
            try {
                $restriction->isAllowed($user->id);
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                $this->setState('membership_restriction_error', true);

                $photos[] = array(
                    'name' => $item['name'],
                    'status' => 0,
                    'message' => $e->getMessage());
                $valid = false;

                continue;
            }

            // Check if upload was successful.
            if (0 != $item['error']) {
                $photos[] = array(
                    'name' => $item['name'],
                    'status' => 0,
                    'message' => $item['error']);
                continue;
            }

            // Check if uploaded file is an image.
            $size = getimagesize($item['tmp_name']);
            if (!$size || !in_array(strtolower(JFile::getExt($item['name'])), array('jpg', 'jpeg', 'gif', 'png'))) {
                $photos[] = array(
                    'name' => $item['name'],
                    'status' => 0,
                    'message' => FactoryText::_('photo_task_upload_error_file_not_image'));
                continue;
            }

            // Check if file size exceeds maximum accepted.
            if ($settings->photos_max_size * 1024 * 1024 < $item['size']) {
                $photos[] = array(
                    'name' => $item['name'],
                    'status' => 0,
                    'message' => FactoryText::sprintf('photo_task_upload_error_file_size_exceeded', $settings->photos_max_size));
                continue;
            }

            $photoPrivacy = isset($privacy[$i]) ? $privacy[$i] : 0;
            $this->uploadImage($item, $user->id, $photoPrivacy);

            $photos[] = array(
                'name' => $item['name'],
                'status' => 1,
                'message' => FactoryText::_('photo_task_upload_success'));
        }

        $this->setState('photos', $photos);

        return $valid;
    }

    /**
     * Adds Gravatar photo.
     * @return bool
     */
    public function addGravatar()
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $table = $this->getTable('Photo');
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        // Check if Gravatar integration is enabled.
        if (!$settings->enable_gravatar_integration) {
            $this->setError(FactoryText::_('photo_task_addgravatar_error_not_enabled'));
            return false;
        }

        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('photos');

        // Check if user is allowed to add any more photos.
        try {
            $restriction->isAllowed($user->id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        $data = array('user_id' => $user->id, 'filename' => 'gravatar');

        if (!$table->save($data)) {
            $this->setError($table->getError());
            return false;
        }

        JEventDispatcher::getInstance()->trigger('onLoveFactoryPhotoUploaded', array(
            'com_lovefactory.photo_uploaded',
            $table
        ));

        return true;
    }

    public function setMain($batch)
    {
        // Initialise variables.
        $table = $this->getTable('Photo');
        $user = JFactory::getUser();

        // Check if any file was uploaded.
        if (!is_array($batch) || !$batch) {
            $this->setError(FactoryText::_('batch_no_item_selected'));
            return false;
        }

        // Check if photo exist.
        if (!$batch[0] || !$table->load($batch[0])) {
            $this->setError(FactoryText::_('photo_task_setmain_error_photo_not_found'));
            return false;
        }

        // Check if user owns photo.
        if ($table->user_id != $user->id) {
            $this->setError(FactoryText::_('photo_task_setmain_error_not_allowed'));
            return false;
        }

        // Check if photo is approved.
        if (LoveFactoryApplication::getInstance()->getSettings('approval_photos', 0) && !$table->approved) {
            $this->setError(FactoryText::_('photo_task_setmain_error_photo_not_approved'));
            return false;
        }

        // Load user profile.
        /* @var $profile TableProfile */
        $profile = $this->getTable('Profile');
        $profile->load($user->id);

        // Set photo as profile photo.
        if (!$profile->setProfilePhoto($table->id)) {
            $this->setError($profile->getError());
            return false;
        }

        $this->setState('photoId', $table->id);

        return true;
    }

    public function uploadImage($image, $userId, $privacy = 0)
    {
        require_once(JPATH_COMPONENT_SITE . DS . 'resizeIMG.php');
        jimport('joomla.filesystem.file');

        $app = LoveFactoryApplication::getInstance();
        $config = JFactory::getConfig();
        $settings = $app->getSettings();
        $extension = strtolower(JFile::getExt($image['name']));
        $filename = md5($userId . time() . rand(0, 1000000)) . '.' . $extension;
        $tmpPath = $config->get('tmp_path') . '/com_lovefactory/' . $filename;

        if (isset($image['error'])) {
            JFile::upload($image['tmp_name'], $tmpPath);
        } else {
            JFile::move($image['tmp_name'], $tmpPath);
        }

        // iPad fix: check for photo orientation and fix it.
        $this->fixOrientation($tmpPath);

        // Resize and move image.
        $filepath = JPath::clean($app->getUserFolder($userId) . DS . 'thumb_' . $filename);

        $resize = new RESIZEIMAGE($tmpPath);
        $resize->resize_scale($settings->thumbnail_max_width, $settings->thumbnail_max_height, $filepath);
        $resize->close();

        $filepath = JPath::clean($app->getUserFolder($userId) . DS . $filename);
        $resize = new RESIZEIMAGE($tmpPath);
        $resize->resize_scale($settings->photo_max_width, $settings->photo_max_height, $filepath);
        $resize->close();

        $data = array(
            'user_id' => $userId,
            'filename' => $filename,
            'status' => $privacy,
        );

        $table = $this->getTable('Photo');
        $table->save($data);

        JEventDispatcher::getInstance()->trigger('onLoveFactoryPhotoUploaded', array(
            'com_lovefactory.photo_uploaded',
            $table
        ));

        JFile::delete($tmpPath);

        return $table;
    }

    public function getApprovalEnabled()
    {
        $approval = LoveFactoryApplication::getInstance()->getSettings('approval_photos', 0);

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
        // Check if photo approvals are enavled and if the photo is approved.
        $approval = LoveFactoryApplication::getInstance()->getSettings('approval_photos', 0);
        if ($approval && !$table->approved && $table->user_id != $user->id) {
            return false;
        }

        // Check if photo is main profile photo and if user can view full profile photos
        // or just the pixelated version.
        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($table->user_id);

        if ($profile->main_photo == $table->id) {
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('avatar_access');
            try {
                $restriction->isAllowed($user->id, $table->user_id);
            } catch (Exception $e) {
                return false;
            }
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

    protected function removeMainPhotoFromProfiles($id)
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_profiles')
            ->set('main_photo = ' . $dbo->quote(0))
            ->where('main_photo = ' . $dbo->quote($id));

        $dbo->setQuery($query)
            ->execute();
    }

    protected function fixOrientation($file)
    {
        if (!function_exists('read_exif_data')) {
            return false;
        }

        // PHP Orientation Fix (GD) v1.0.0
        // https://github.com/leesherwood/Orientation-Fix-PHP

        $exif = read_exif_data($file, 'IFD0');

        if (!$exif || !is_array($exif)) {
            return false;
        }

        $exif = array_change_key_case($exif, CASE_LOWER);

        if (!array_key_exists('orientation', $exif)) {
            return false;
        }

        $img_res = $this->get_image_resource($file);

        if (null === $img_res) {
            return false;
        }

        switch ($exif['orientation']) {
            // Standard/Normal Orientation (no need to do anything, we'll return true as in theory, it was successful)
            case 1:
                return true;
                break;

            // Correct orientation, but flipped on the horizontal axis (might do it at some point in the future)
            case 2:
                $final_img = $this->imageflip($img_res, 1);
                break;

            // Upside-Down
            case 3:
                $final_img = $this->imageflip($img_res, 2);
                break;

            // Upside-Down & Flipped along horizontal axis
            case 4:
                $final_img = $this->imageflip($img_res, 3);
                break;

            // Turned 90 deg to the left and flipped
            case 5:
                $final_img = imagerotate($img_res, -90, 0);
                $final_img = $this->imageflip($img_res, 1);
                break;

            // Turned 90 deg to the left
            case 6:
                $final_img = imagerotate($img_res, -90, 0);
                break;

            // Turned 90 deg to the right and flipped
            case 7:
                $final_img = imagerotate($img_res, 90, 0);
                $final_img = $this->imageflip($img_res, 1);
                break;

            // Turned 90 deg to the right
            case 8:
                $final_img = imagerotate($img_res, 90, 0);
                break;
        }

        if (!isset($final_img)) {
            return false;
        }

        $this->save_image_resource($final_img, $file);
    }

    protected function save_image_resource($resource, $location)
    {
        $success = false;
        $p = explode(".", strtolower($location));
        $ext = array_pop($p);

        switch ($ext) {
            case "png":
                $success = imagepng($resource, $location);
                break;

            case "jpg":
            case "jpeg":
                $success = imagejpeg($resource, $location);
                break;

            case "gif":
                $success = imagegif($resource, $location);
                break;
        }

        return $success;
    }

    protected function get_image_resource($file)
    {
        $img = null;
        $p = explode(".", strtolower($file));
        $ext = array_pop($p);

        switch ($ext) {
            case "png":
                $img = imagecreatefrompng($file);
                break;

            case "jpg":
            case "jpeg":
                $img = imagecreatefromjpeg($file);
                break;

            case "gif":
                $img = imagecreatefromgif($file);
                break;
        }

        return $img;
    }

    protected function imageflip($imgsrc, $mode)
    {
        $width = imagesx($imgsrc);
        $height = imagesy($imgsrc);

        $src_x = 0;
        $src_y = 0;
        $src_width = $width;
        $src_height = $height;

        switch ($mode) {
            case 2: //vertical
                $src_y = $height - 1;
                $src_height = -$height;
                break;

            case 1: //horizontal
                $src_x = $width - 1;
                $src_width = -$width;
                break;

            case 3: //both
                $src_x = $width - 1;
                $src_y = $height - 1;
                $src_width = -$width;
                $src_height = -$height;
                break;

            default:
                return $imgsrc;
        }

        $imgdest = imagecreatetruecolor($width, $height);

        if (imagecopyresampled($imgdest, $imgsrc, 0, 0, $src_x, $src_y, $width, $height, $src_width, $src_height)) {
            return $imgdest;
        }

        return $imgsrc;
    }
}
