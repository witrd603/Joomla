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

class LoveFactoryFieldProfilePhoto extends LoveFactoryField
{
    protected $accessPageBlackList = array('search_quick', 'search_advanced', 'radius_search');
    protected $isPublic = true;

    public function __construct($type, $field = null, $mode = 'view')
    {
        parent::__construct($type, $field, $mode);

        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $this->addHelpText(FactoryText::sprintf('field_profile_photo_upload_max_file_size', $settings->photos_max_size), 'fa-exclamation-circle');
    }

    public function renderInputView()
    {
        $photo = $this->getProfilePhoto($this->userId);

        $html = array();

        if ('gallery' === $this->params->get('url_redirect', 'gallery')) {
            $redirect = FactoryRoute::view('photos&user_id=' . $this->userId);
        } else {
            $redirect = FactoryRoute::view('profile&user_id=' . $this->userId);
        }

        if (JFactory::getApplication()->isAdmin()) {
            $redirect = str_replace('/administrator', '', $redirect);
        }

        $html[] = '<div style="background-image: url(\'' . $photo . '\');" class="lovefactory-thumbnail">';
        $html[] = '<a href="' . $redirect . '" style="display: block; height: 100%;"></a>';
        $html[] = '</div>';

        return implode("\n", $html);
    }

    public function renderInputEdit()
    {
        $html = array();

        if (!$this->isAjaxUploadEnabled()) {
            $html[] = $this->renderInputView();
            $html[] = '<input id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" type="file" />';
        } else {
            $upload = JRoute::_('index.php?option=com_lovefactory&controller=field&task=action&format=raw&field=profilephoto&instance=' . $this->field->id . '&action=upload');

            $html[] = '<div class="ajax-upload">';

            $html[] = '<div class="ajax-upload-photo">';

            if ($this->data) {
                if (false !== strpos($this->data, '.')) {
                    $url = JUri::root() . 'tmp/com_lovefactory/thumb_' . $this->data;
                } else {
                    $url = $this->getProfilePhoto(JFactory::getUser()->id);
                }

                $html[] = '<div style="background-image: url(' . $url . '); margin-bottom: 10px;" class="lovefactory-thumbnail"></div>';
            }

            $html[] = '</div>';

            $html[] = '<div class="progress progress-striped active" style="display: none; margin: 10px 0; border-radius: 0; height: 10px;"><div class="bar" style="width: 0;"></div></div>';

            $html[] = '<div class="ajax-upload-actions">';
            $html[] = '<a href="' . $upload . '" class="btn btn-small btn-primary" data-action="select">' . FactoryText::_('field_profile_photo_select_photo') . '</a>';

            $display = $this->data ? '' : 'none';
            $html[] = '<a href="#" class="btn btn-small btn-danger" data-action="remove" style="display: ' . $display . ';">' . FactoryText::_('field_profile_photo_remove_photo') . '</a>';

            $html[] = '</div>';

            $html[] = '<input type="file" style="display: none;" />';

            $value = $this->data ? $this->data : '';
            $html[] = '<input id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" type="hidden" value="' . $value . '" />';

            $html[] = '</div>';

            JFactory::getDocument()->addScript('administrator/components/com_lovefactory/lib/fields/profilephoto/script.js');
        }

        return implode("\n", $html);
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        if (is_null($this->data)) {
            return true;
        }

        if (!$this->isAjaxUploadEnabled()) {
            // Check if there were errors uploading the file.
            if (0 != $this->data['error']) {
                if (1 == $this->data['error']) {
                    $this->setError(FactoryText::sprintf('field_profile_photo_error_upload_too_large', $this->getLabel()));
                } else {
                    $this->setError(FactoryText::sprintf('field_profile_photo_error_upload', $this->data['error'], $this->getLabel()));
                }

                return false;
            }
        } else {
            if ('' === $this->data) {
                $this->setError(FactoryText::sprintf('field_is_required', $this->getLabel()));
                return false;
            }

            if (false === strpos($this->data, '.')) {
                $profile = $this->getProfile(JFactory::getUser()->id);

                if ($profile->main_photo == $this->data) {
                    return true;
                }
            }

            // Check if file exists.
            $config = JFactory::getConfig();
            $temp = $config->get('tmp_path');

            $filepath = $temp . '/com_lovefactory/' . $this->data;

            if (!file_exists($filepath)) {
                $this->setError(FactoryText::_('field_profile_photo_error_upload_not_found'));
                return false;
            }
        }

        // Check if user has a membership assigned.
        $profile = $this->getPage()->getData();

        if ($profile instanceof TableProfile) {
            $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('photos');
            try {
                $restriction->isAllowed($profile->user_id);
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                return false;
            }
        }

        if (!$this->isAjaxUploadEnabled()) {
            // Check if uploaded file is an image.
            $image = getimagesize($this->data['tmp_name']);

            if (!$image || !in_array($image[2], array(1, 2, 3))) {
                $this->setError(FactoryText::sprintf('field_profile_photo_error_not_image', $this->getLabel()));
                return false;
            }
        }

        return true;
    }

    public function getId()
    {
        return 'main_photo';
    }

    public function postProfileSave($profile)
    {
        if (is_null($this->data)) {
            return true;
        }

        /* @var $model FrontendModelPhoto */
        $model = JModelLegacy::getInstance('Photo', 'FrontendModel');

        if (!$this->isAjaxUploadEnabled()) {
            $photo = $model->uploadImage($this->data, $profile->user_id);
        } else {
            if (false === strpos($this->data, '.')) {
                return true;
            }

            $config = JFactory::getConfig();
            $temp = $config->get('tmp_path');
            $tmp_name = $temp . '/com_lovefactory/' . $this->data;

            $data = array(
                'name' => $this->data,
                'tmp_name' => $tmp_name,
            );
            $photo = $model->uploadImage($data, $profile->user_id);
        }

        if ($profile->setProfilePhoto($photo->id)) {
            $profile->main_photo = $photo->id;
        }

        return true;
    }

    public function filterData()
    {
        if (!$this->isAjaxUploadEnabled()) {
            if (!is_array($this->data) || !isset($this->data['error']) || 4 == $this->data['error']) {
                $this->data = null;
            }
        }

        return true;
    }

    protected function getProfilePhoto($userId)
    {
        $profile = $this->getProfile($userId);

        return $profile->getProfilePhotoSource(true);
    }

    protected function getProfile($userId)
    {
        /* @var $profile TableProfile */
        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($userId);

        return $profile;
    }

    private function isAjaxUploadEnabled()
    {
        return (boolean)$this->getParam('ajax_upload', 0);
    }
}
