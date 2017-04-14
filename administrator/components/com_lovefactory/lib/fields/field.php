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

abstract class LoveFactoryField extends JObject
{
    protected $type = null;
    protected $field;
    protected $mode;
    protected $params = null;
    protected $titles;
    protected $userId;
    protected $isFriend;
    protected $data = null;
    protected $originalData = null;
    protected $visibility;
    protected $labels = null;
    protected $descriptions = null;
    protected $generatesDataColumn = false;
    protected $generatesVisibilityColumn = false;
    protected $formControl = 'form';
    protected $page = null;
    protected $accessPageWhiteList = array();
    protected $accessPageBlackList = array();
    protected $_errors = array();
    protected $privacy = null;
    protected $isAdmin = null;
    protected $myProfile = null;
    protected $renderable = null;
    protected $helpText = array();
    protected $settings;
    protected $customCss = null;
    protected $showLabel = null;
    protected $isPublic = false;

    public function __construct($type, $field = null, $mode = 'view')
    {
        $this->type = $type;
        $this->mode = $mode;
        $this->field = $field;

        $this->params = new JRegistry(isset($field->params) ? $field->params : '');
        $this->titles = new JRegistry(isset($field->titles) ? $field->titles : '');
    }

    public static function getInstance($type, $field = null, $mode = 'view')
    {
        $class = 'LoveFactoryField' . ucfirst($type);

        if (!class_exists($class)) {
            JLoader::register($class, LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'lib' . DS . 'fields' . DS . strtolower($type) . DS . 'field.php');
        }

        if (!class_exists($class)) {
            throw new Exception(sprintf('Could not instantiate field type "%s".', $class));
        }

        return new $class($type, $field, $mode);
    }

    public function getType()
    {
        return str_replace('LoveFactoryField', '', get_class($this));
    }

    public function getBaseType()
    {
        return $this->type;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getLabels()
    {
        if (is_null($this->labels)) {
            $this->labels = new JRegistry($this->field->labels);
        }

        return $this->labels;
    }

    public function getDescriptions()
    {
        if (is_null($this->descriptions)) {
            $this->descriptions = new JRegistry($this->field->descriptions);
        }

        return $this->descriptions;
    }

    public function getParams()
    {
        if (is_null($this->params)) {
            $this->params = new JRegistry($this->field->params);
        }

        return $this->params;
    }

    public function getParam($param, $default = null)
    {
        return $this->getParams()->get($param, $default);
    }

    public function setParam($param, $value = null)
    {
        return $this->getParams()->set($param, $value);
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function setGeneratesDataColumn($generatesDataColumn)
    {
        $this->generatesDataColumn = $generatesDataColumn;
    }

    public function getAccessPageWhiteList()
    {
        return $this->accessPageWhiteList;
    }

    public function getAccessPageBlackList()
    {
        return $this->accessPageBlackList;
    }

    public function getGeneratesDataColumn()
    {
        return $this->generatesDataColumn;
    }

    public function setGeneratesVisibilityColumn($generatesVisibilityColumn)
    {
        $this->generatesVisibilityColumn = $generatesVisibilityColumn;
    }

    public function getGeneratesVisibilityColumn()
    {
        return $this->generatesVisibilityColumn;
    }

    public function setFormControl($formControl)
    {
        $this->formControl = $formControl;
    }

    public function getFormControl()
    {
        return $this->formControl;
    }

    public function bind($data)
    {
        $this->data = null;

        if (is_null($data)) {
            return false;
        }

        if ($data instanceof TableProfile) {
            $this->data = $data->getSource($this->getId());
        }

        if (is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data['user_id'])) {
            $this->userId = $data['user_id'];
        }

        if (isset($data['is_friend'])) {
            $this->isFriend = $data['is_friend'];
        }

        if (isset($data[$this->getId()])) {
            $this->data = $data[$this->getId()];
        }

        if ($this->field->user_visibility && isset($data[$this->getVisibilityId()])) {
            $this->visibility = $data[$this->getVisibilityId()];
        }

        return true;
    }

    public function bindValue($data)
    {
        $this->data = $data;
    }

    public function bindOriginalData($data)
    {
        $this->originalData = null;

        if (is_null($data)) {
            return false;
        }

        if (is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data[$this->getId()])) {
            $this->originalData = $data[$this->getId()];
        }
    }

    public function bindDataToProfile($profile)
    {
        // Check if field is editable.
        if ($this->isEditable()) {
            $profile[$this->getId()] = $this->convertDataToProfile();
        }

        // Check if user can update user privacy.
        if ($this->hasUserPrivacy()) {
            $profile[$this->getVisibilityId()] = $this->getVisibility();
        }

        return $profile;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getFilteredData()
    {
        $data = array();

        if (!is_null($this->data)) {
            $data[$this->getId()] = $this->data;
        }

        if (!is_null($this->visibility)) {
            $data[$this->getVisibilityId()] = $this->visibility;
        }

        return $data;
    }

    public function getVisibility()
    {
        $visibilities = array_flip($this->getVisibilityMapping());

        return isset($visibilities[$this->visibility]) ? $visibilities[$this->visibility] : null;
    }

    public function validate()
    {
        if ($this->field->required && is_null($this->data)) {
            $this->setError(FactoryText::sprintf('field_is_required', $this->getLabel()));
            return false;
        }

        return true;
    }

    public function validateData()
    {
        if ($this->field->lock_after_save && !is_null($this->originalData)) {
            return true;
        }

        return $this->validate();
    }

    public function filterData()
    {
        return true;
    }

    public function postProfileSave($profile)
    {
        return true;
    }

    public function convertDataToProfile()
    {
//    if ($this->field->lock_after_save && !is_null($this->originalData)) {
//      return null;
//    }

        return $this->data;
    }

    public function addQuerySearchCondition($query)
    {
        $condition = $this->getQuerySearchCondition($query);

        if (false === $condition) {
            return false;
        }

        if ($this->field->user_visibility) {
            // User set visibility
            $query->where(
            //' (CASE p.field_' . $this->field->id . '_visibility' .
                ' (CASE p.' . $query->quoteName($this->getVisibilityId()) .
                '   WHEN 1 THEN ' . $condition .
                '   WHEN 2 THEN (' . $condition . ' AND f.id IS NOT NULL)' .
                '   WHEN 0 THEN 1 = 0' .
                ' END)');
        } elseif (1 == $this->field->visibility) {
            // Visible for all
            $query->where($condition);
        } elseif (2 == $this->field->visibility) {
            // Visible for friends
            $query->where('((' . $condition . ') AND f.id IS NOT NULL)');
        }

        return $query;
    }

    public function getQuerySearchCondition($query)
    {
        return false;
    }

    public function addQueryView($query)
    {
        $this->getQueryView($query);
    }

    public function getQueryView($query)
    {
        return $query;
    }

    protected function getCalculatedVisibility()
    {
        // Get user set visibility.
        $visibility = $this->field->visibility;

        // Check if user is allowed to set own visibility.
        if ($this->field->user_visibility) {
            $visibility = $this->visibility;
        }

        return $visibility;
    }

    protected function getVisibilityMapping()
    {
        $visibilities = array(
            1 => 'public',
            2 => 'friends',
            0 => 'private',
        );

        return $visibilities;
    }

    public function renderFieldVisibility()
    {
        $visibilities = $this->getVisibilityMapping();

        if ($this->field->admin_only_viewable) {
            return '<i class="factory-icon icon-exclamation hasTip" title="' . FactoryText::_('field_viewable_only_by_admin_title') . '::' . FactoryText::_('field_viewable_only_by_admin_text') . '"></i>';
        }

        if (!$this->field->user_visibility || !$this->getGeneratesVisibilityColumn()) {
            if (1 != $this->field->visibility) {
                $visibility = $visibilities[$this->field->visibility];

                return JHtml::_('LoveFactory.privacyButton', $visibility, array(
                    'readonly' => true
                ));
            }

            return '';
        }

        if (is_numeric($this->visibility)) {
            $visibility = $visibilities[$this->visibility];
        } else {
            $visibility = $this->visibility;
        }

        if (is_null($visibility)) {
            $visibility = 'public';
        }

        return JHtml::_('LoveFactory.privacyButton', $visibility, array(
            'hiddenInput' => true,
            'hiddenInputName' => $this->getHtmlVisibilityName(),
        ));
    }

    public function renderInputView()
    {
        return $this->getId();
    }

    public function renderInputEdit()
    {
        return $this->getId();
    }

    public function renderInputSearch()
    {
        return $this->renderInputEdit();
    }

    public static function renderInputBlank()
    {
        return '-';
    }

    public function showDescription()
    {
        $descriptions = $this->getDescriptions();
        $prefix = $this->mode;

        return $descriptions->get($prefix . '.enabled', 1);
    }

    public function renderLabel()
    {
        $method = 'renderLabel' . ucfirst($this->mode);

        return call_user_func_array(array($this, $method), array());
    }

    public function renderDescription()
    {
        $description = $this->getDescription();

        if (!$description) {
            return '';
        }

        JHtml::_('behavior.tooltip');

        return '<i class="factory-icon icon-question-frame hasTip" title="' . htmlentities($this->getLabel(), ENT_COMPAT, 'UTF-8') . '::' . htmlentities(nl2br($description), ENT_COMPAT, 'UTF-8') . '"></i>';
    }

    public function renderLabelView()
    {
        return '<label for="' . $this->getHtmlId() . '">' . $this->getLabel() . '</label>';
    }

    public function renderLabelSearch()
    {
        return $this->getLabel();
        return $this->renderLabelView();
    }

    public function getDescription()
    {
        $descriptions = $this->getDescriptions();
        $language = JFactory::getLanguage()->getTag();
        $prefix = $this->mode;

        $default = $descriptions->get($prefix . '.default', '');
        $description = $descriptions->get($prefix . '.' . $language, $default);

        return $description;
    }

    public function getId()
    {
        return 'field_' . $this->field->id;
    }

    public function getFieldId()
    {
        return $this->field->id;
    }

    public function getVisibilityId()
    {
        return $this->getId() . '_visibility';
    }

    public function getHtmlId()
    {
        return $this->getFormControl() . '_' . $this->getId();
    }

    public function getHtmlName()
    {
        return $this->getFormControl() . '[' . $this->getId() . ']';
    }

    public function getHtmlVisibilityName()
    {
        return 'form[' . $this->getId() . '_visibility]';
    }

    public function getContainerHtmlClass()
    {
        return $this->getHtmlId() . '_container';
    }

    public function getChoices()
    {
        $language = JFactory::getLanguage();
        $default = $this->params->get('choices.default', array());
        $translation = $this->params->get('choices.' . $language->getTag(), array());

        foreach ($default as $key => $value) {
            if (isset($translation[$key])) {
                $default[$key] = $translation[$key];
            }
        }

        return $default;
    }

    protected function addQueryElement($query, $type, $element, $subtype = null)
    {
        if (is_null($subtype)) {
            $subtype = $type;
        }

        $types = $query->$type;

        if (!is_array($types)) {
            $types = array($types);
        }

        foreach ($types as $queryType) {
            foreach ($queryType->getElements() as $queryElement) {
                if ($queryElement == $element) {
                    return true;
                }
            }
        }

        $query->$subtype($element);
    }

    public function getQueryAlterProfileTableInsertColumn($dbo)
    {
        $type = 'JDatabaseDriverPostgresql' == get_class(JFactory::getDbo()) ? 'text' : 'MEDIUMTEXT';

        $query = ' ALTER TABLE #__lovefactory_profiles ADD COLUMN ' . $dbo->quoteName($this->getId()) . ' ' . $type;

        return $query;
    }

    public function getQueryAlterProfileTableInsertVisibilityColumn($dbo)
    {
        $type = 'JDatabaseDriverPostgresql' == get_class(JFactory::getDbo()) ? 'smallint' : 'TINYINT(1)';

        $query = ' ALTER TABLE #__lovefactory_profiles ADD COLUMN ' . $dbo->quoteName($this->getVisibilityId()) . ' ' . $type;

        return $query;
    }

    public function getQueryAlterProfileTableDropColumn($dbo)
    {
        $query = ' ALTER TABLE #__lovefactory_profiles DROP COLUMN ' . $dbo->quoteName($this->getId());

        return $query;
    }

    public function getQueryAlterProfileTableDropVisibilityColumn($dbo)
    {
        $query = ' ALTER TABLE #__lovefactory_profiles DROP COLUMN ' . $dbo->quoteName($this->getVisibilityId());

        return $query;
    }

    public function getProfileTableColumnName()
    {
        return $this->getId();
    }

    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null) {
            // Default, return the last message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // If $i has been specified but does not exist, return false
            return false;
        } else {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString) {
            return (string)$error;
        }

        return $error;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setError($error)
    {
        array_push($this->_errors, $error);
    }

    public function isPublished()
    {
        return $this->field->published;
    }

    public function isRenderable()
    {
        if (null === $this->renderable) {
            // Initialise renderable.
            $this->renderable = true;

            // Check if field is published.
            if (!$this->isPublished()) {
                $this->renderable = false;
            } // If user is not admin, perform aditional checks.
            elseif (!$this->isAdmin()) {
                if ('edit' === $this->getMode()) {
                    $this->renderable = $this->isEditRenderable();
                } elseif ('view' === $this->getMode()) {
                    $this->renderable = $this->isViewRenderable();
                } else {
                    $this->renderable = $this->isSearchRenderable();
                }
            }
        }

        return $this->renderable;
    }

    public function isRequired()
    {
        return 'edit' == $this->getMode() && $this->field->required;
    }

    public function isPublic()
    {
        return $this->isPublic;
    }

    public function isAdminOnlyViewable()
    {
        return $this->field->admin_only_viewable;
    }

    public function isAdminOnlyEditable()
    {
        return $this->field->admin_only_editable;
    }

    public function isLockedAfterSave()
    {
        return $this->field->lock_after_save;
    }

    public function hasBeenLockedAfterSave()
    {
        return null !== $this->originalData && '' !== $this->originalData;
    }

    public function hasUserPrivacy()
    {
        if ($this->isPublic) {
            return false;
        }

        return $this->field->user_visibility;
    }

    public function hasLabel($mode)
    {
        if (null === $this->showLabel) {
            $this->showLabel = $this->getLabels()->get($mode . '.enabled', 1);
        }

        return $this->showLabel;
    }

    public function getHelpText()
    {
        return $this->helpText;
    }

    public function getPrivacy()
    {
        if ($this->isPublic) {
            return 'public';
        }

        if ($this->hasUserPrivacy()) {
            if (null === $this->visibility) {
                $privacy = $this->field->visibility;
            } else {
                $privacy = $this->visibility;
            }
        } else {
            $privacy = $this->field->visibility;
        }

        if (in_array($privacy, array('public', 'private', 'friends'))) {
            $this->privacy = $privacy;
        } elseif (null === $privacy || 1 == $privacy) {
            $this->privacy = 'public';
        } elseif (0 == $privacy) {
            $this->privacy = 'private';
        } else {
            $this->privacy = 'friends';
        }

        return $this->privacy;
    }

    public function getLabel()
    {
        $labels = $this->getLabels();
        $language = JFactory::getLanguage()->getTag();
        $prefix = $this->mode;

        $default = $labels->get($prefix . '.default', $this->field->title);
        $label = $labels->get($prefix . '.' . $language, $default);

        return $label;
    }

    public function addHelpText($message, $icon = 'fa-exclamation')
    {
        $this->helpText[] = array('message' => $message, 'icon' => $icon);
    }

    public function renderEditable()
    {
        $this->preRender();

        // Make sure we are showing original data.
        if ($this->isLockedAfterSave() && $this->hasBeenLockedAfterSave()) {
            $this->data = $this->originalData;
        }

        return $this->renderInputEdit();
    }

    public function renderViewable()
    {
        $this->preRender();

        // Get field privacy.
        $privacy = $this->getPrivacy();

        // If this is the user's profile, field privacy is set to public
        // or we're on backend, then show the field.
        if ('public' === $privacy || $this->isMyProfile() || $this->isAdmin()) {
            return $this->renderInputView();
        }

        // Check if privacy is set to friends.
        if ('friends' === $privacy) {
            // If users are friends, show the field.
            if ($this->isFriend) {
                return $this->renderInputView();
            }

            return FactoryText::_('field_available_only_for_friends');
        }

        // Check if privacy is set to private.
        // We know for sure that this is not the user's profile from the check above,
        // so just display the field private message.
        if ('private' === $privacy) {
            return FactoryText::_('field_private');
        }

        return;
    }

    public function renderSearchable()
    {
        $this->preRender();

        return $this->renderInputSearch();
    }

    protected function isMyProfile()
    {
        $this->myProfile = !JFactory::getUser()->guest && JFactory::getUser()->id == $this->userId;

        return $this->myProfile;
    }

    protected function isEditable()
    {
        // Check if user is administrator, administrators are always allowed to edit field.
        if ($this->isAdmin()) {
            return true;
        }

        // Check if field is being locked after save and if the field has already been locked.
        if ($this->isLockedAfterSave() && $this->hasBeenLockedAfterSave()) {
            return false;
        }

        return true;
    }

    protected function isAdmin()
    {
        if (null === $this->isAdmin) {
            $this->isAdmin = $this->getPage()->getIsAdmin();
        }

        return $this->isAdmin;
    }

    protected function isViewRenderable()
    {
        // If this field is viewable only by admins, do not show the field.
        if ($this->isAdminOnlyViewable()) {
            return false;
        }

        // If the is the user's profile, show the field.
        if ($this->isMyProfile()) {
            return true;
        }

        // Get settings.
        $settings = $this->getSettings();

        // Check if we display field status, instead of not showing at all
        if ($settings->display_hidden) {
            return true;
        }

        // Check if if field is hidden or for firends.
        $privacy = $this->getPrivacy();
        if ('private' === $privacy || ('friends' === $privacy && !$this->isFriend)) {
            return false;
        }

        return true;
    }

    protected function isEditRenderable()
    {
        if ($this->isAdminOnlyEditable()) {
            return false;
        }

        return true;
    }

    protected function isSearchRenderable()
    {
        if ($this->isAdminOnlyViewable()) {
            return false;
        }

        return true;
    }

    protected function getSettings()
    {
        if (null === $this->settings) {
            $this->settings = $this->getPage()->getSettings();
        }

        return $this->settings;
    }

    protected function preRender()
    {
        $this->renderCustomCss($this->getMode());
    }

    protected function renderCustomCss($mode)
    {
        $customCss = $this->getCustomCss($mode);

        if (false === $customCss) {
            return;
        }

        JFactory::getDocument()->addStyleDeclaration($customCss);
    }

    public function getCustomCss($mode)
    {
        if (null === $this->customCss) {
            $this->customCss = false;

            $css = new \Joomla\Registry\Registry($this->field->css);
            $enabled = $css->get($mode . '.enabled', 0);

            if ($enabled) {
                $cssDeclaration = $css->get($this->mode . '.css', '');

                if ('' != $cssDeclaration) {
                    $this->customCss = $cssDeclaration;
                }
            }
        }

        return $this->customCss;
    }

    public function getDisplayData()
    {
        return $this->data;
    }
}
