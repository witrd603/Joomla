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

class ProfilePhotoActions
{
    public function upload()
    {
        $config = JFactory::getConfig();
        $input = JFactory::getApplication()->input;
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $file = $input->files->get('file');

        $size = getimagesize($file['tmp_name']);
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $valid = in_array($extension, array('jpg', 'jpeg', 'gif', 'png'));

        // Check if file is an image and has the correct extension.
        if (!$size || !$valid) {
            throw new Exception(FactoryText::_('photo_task_upload_error_file_not_image'));
        }

        // Check if file size exceeds maximum accepted.
        if ($settings->photos_max_size * 1024 * 1024 < $file['size']) {
            throw new Exception(FactoryText::sprintf('photo_task_upload_error_file_size_exceeded', $settings->photos_max_size));
        }

        jimport('joomla.filesyste.file');

        $filename = uniqid() . '.' . $extension;
        $temp = $config->get('tmp_path');
        $dest = $temp . '/com_lovefactory/' . $filename;

        JFile::upload($file['tmp_name'], $dest);

        require_once(JPATH_COMPONENT_SITE . DS . 'resizeIMG.php');

        $resize = new RESIZEIMAGE($dest);
        $thumb = 'thumb_' . $filename;

        $resize->resize_scale($settings->thumbnail_max_width, $settings->thumbnail_max_height, $temp . '/com_lovefactory/' . $thumb);
        $resize->close();

        return array(
            'filename' => $filename,
            'path' => JUri::root() . 'tmp/com_lovefactory/' . $filename,
            'thumb' => JUri::root() . 'tmp/com_lovefactory/' . $thumb,
        );
    }
}
