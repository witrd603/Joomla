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

class BackendControllerFieldImage extends JControllerLegacy
{
    public function upload()
    {
        $id = $this->input->post->getInt('id');
        $height = $this->input->post->getInt('height', 100);
        $width = $this->input->post->getInt('width', 100);
        $fieldId = $this->input->get->getInt('field_id');
        $file = $this->input->files->get('file', array(), 'array');

        jimport('joomla.filesystem.folder');

        if (!JFolder::exists(JPATH_SITE . '/media/com_lovefactory/storage/fieldimage/' . $fieldId)) {
            JFolder::create(JPATH_SITE . '/media/com_lovefactory/storage/fieldimage/' . $fieldId);
        }

        require_once(JPATH_COMPONENT_SITE . DS . 'resizeIMG.php');

        $resize = new RESIZEIMAGE($file['tmp_name']);

        $resize->resize_scale(
            $width,
            $height,
            JPATH_SITE . '/media/com_lovefactory/storage/fieldimage/' . $fieldId . '/' . $id . '.png'
        );
        $resize->close();

        echo json_encode(array(
            'src' => JUri::root() . 'media/com_lovefactory/storage/fieldimage/' . $fieldId . '/' . $id . '.png'
        ));

        jexit();
    }

    public function remove()
    {
        $id = $this->input->get->getInt('id');
        $fieldId = $this->input->get->getInt('field_id');

        jimport('joomla.filesystem.file');

        if (JFile::exists(JPATH_SITE . '/media/com_lovefactory/storage/fieldimage/' . $fieldId . '/' . $id . '.png')) {
            JFile::delete(JPATH_SITE . '/media/com_lovefactory/storage/fieldimage/' . $fieldId . '/' . $id . '.png');
        }

        echo json_encode(array());

        jexit();
    }
}
