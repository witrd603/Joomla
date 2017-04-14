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

class LoveFactoryPage extends JObject
{
    protected $page;
    protected $mode;
    protected $data;
    protected $fields = null;
    protected $tablePage = null;
    protected $titlePlaceholders = null;
    protected $lastErrors = array();
    protected $config = array();
    protected $formControl = 'form';
    protected $isAdmin = false;
    protected $_errors = array();
    protected $settings = null;

    public function __construct($page, $mode, $config = array())
    {
        $this->page = $page;
        $this->mode = $mode;

        $session = JFactory::getSession();
        $this->lastErrors = $session->get('com_lovefactory.page.errors', array());
        $session->set('com_lovefactory.page.errors', array());

        if (isset($config['renderErrorsIndividual'])) {
            $this->config['renderErrorsIndividual'] = $config['renderErrorsIndividual'];
        } else {
            $this->config['renderErrorsIndividual'] = false;
        }

        if (isset($config['formControl'])) {
            $this->setFormControl($config['formControl']);
        }

        if (isset($config['isAdmin'])) {
            $this->setIsAdmin($config['isAdmin']);
        }

        if (isset($config['titlePlaceholders'])) {
            $this->setTitlePlaceholders($config['titlePlaceholders']);
        }

        if (isset($config['settings'])) {
            $this->settings = $config['settings'];
        } else {
            $this->settings = LoveFactoryApplication::getInstance()->getSettings();
        }
    }

    public static function getInstance($page, $mode, $config = array())
    {
        static $instances = array();

        $hash = md5($page . $mode);

        if (!isset($instances[$hash])) {
            $instances[$hash] = new self($page, $mode, $config);
        }

        return $instances[$hash];
    }

    public function getConfigParam($param, $default = null)
    {
        if (isset($this->config[$param])) {
            return $this->config[$param];
        }

        return $default;
    }

    protected function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function getIsAdmin()
    {
        return (boolean)$this->isAdmin;
    }

    public function getZones()
    {
        $zones = array();
        $page = $this->getTablePage($this->page);
        $fields = $this->getFields();
        $language = JFactory::getLanguage()->getTag();

        $registry = new JRegistry($page->fields);
        $setup = $registry->toArray();

        foreach ($setup as $zoneId => $zone) {
            if (!isset($zone['setup']) || !$zone['setup']) {
                continue;
            }

            $titles = $this->getZoneTitles($zone['titles']);

            foreach ($zone['setup'] as $columnId => $column) {
                if (!$column) {
                    continue;
                }

                foreach ($column as $fieldId) {
                    if (!isset($zones[$zoneId])) {
                        $zones[$zoneId] = array('title' => $titles->get($language, $titles->get('default', '')), 'columns' => array());
                    }

                    if (!isset($zones[$zoneId]['columns'][$columnId])) {
                        $zones[$zoneId]['columns'][$columnId] = array();
                    }

                    if (isset($fields[$fieldId])) {
                        $field = $fields[$fieldId];

                        if (isset($this->lastErrors[$field->getId()])) {
                            $field->setError($this->lastErrors[$field->getId()]);
                        }

                        $zones[$zoneId]['columns'][$columnId][] = $field;
                    }
                }

                if (isset($zone['columns'][$columnId])) {
                    $zones[$zoneId]['width'][$columnId] = $zone['columns'][$columnId];
                }
            }
        }

        return $zones;
    }

    public function bind($data)
    {
        $fields = $this->getFields(false);

        foreach ($fields as $field) {
            $field->bind($data);
        }

        $this->data = $data;

        return true;
    }

    public function bindOriginalProfile($data)
    {
        $fields = $this->getFields(false);

        foreach ($fields as $field) {
            $field->bindOriginalData($data);
        }

        $this->data = $data;

        return true;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getFilteredData()
    {
        $fields = $this->getFields();
        $data = array();

        foreach ($fields as $field) {
            if (!$field->isRenderable()) {
                continue;
            }

            $data = array_merge($data, $field->getFilteredData());
        }

        return $data;
    }

    public function setFormControl($formControl)
    {
        $this->formControl = $formControl;

        foreach ($this->getFields() as $field) {
            $field->setFormControl($formControl);
        }
    }

    public function getFormControl()
    {
        return $this->formControl;
    }

    public function validate()
    {
        $valid = true;
        $fields = $this->getFields();
        $errors = array();

        foreach ($fields as $field) {
            if (!$field->isRenderable()) {
                continue;
            }

            $field->filterData();

            if (!$field->validateData()) {
                $valid = false;
                $this->setError($field->getError());

                $errors[$field->getId()] = $field->getError();
            }
        }

        $session = JFactory::getSession();
        $session->set('com_lovefactory.page.errors', $errors);

        return $valid;
    }

    public function filterData()
    {
        $fields = $this->getFields();

        foreach ($fields as $field) {
            if (!$field->isRenderable()) {
                continue;
            }

            $field->filterData();
        }
    }

    public function convertDataToProfile()
    {
        /* @var $field LoveFactoryField */

        $profile = array();
        $fields = $this->getFields();

        foreach ($fields as $field) {
            $profile = $field->bindDataToProfile($profile);
        }

        return $profile;
    }

    public function setTitlePlaceholders($placeholders = array())
    {
        $this->titlePlaceholders = $placeholders;
    }

    public function hasRequiredFields()
    {
        $fields = $this->getFields();

        foreach ($fields as $field) {
            if ($field->isRequired()) {
                return true;
            }
        }

        return false;
    }

    public function postProfileSave($profile)
    {
        $fields = $this->getFields();

        foreach ($fields as $field) {
            $field->postProfileSave($profile);
        }

        return true;
    }

    public function getFields($onlyRenderable = true)
    {
        $fields = $this->getAllFields();

        if (!$onlyRenderable) {
            return $fields;
        }

        foreach ($fields as $i => $field) {
            if (!$field->isRenderable()) {
                unset($fields[$i]);
            }
        }

        return $fields;
    }

    public function getType()
    {
        return $this->page;
    }

    protected function getAllFields()
    {
        if (is_null($this->fields)) {
            $this->fields = array();
            $array = array();
            $page = $this->getTablePage($this->page);

            $registry = new JRegistry($page->fields);
            $setup = $registry->toArray();

            foreach ($setup as $zone) {
                if (!isset($zone['setup']) || !$zone['setup']) {
                    continue;
                }

                foreach ($zone['setup'] as $column) {
                    if (!$column) {
                        continue;
                    }

                    foreach ($column as $field) {
                        $array[] = $field;
                    }
                }
            }

            if (!$array) {
                $this->fields = array();
                return $this->fields;
            }

            JLoader::register('TableField', LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'tables' . DS . 'field.php');
            $dbo = JFactory::getDbo();
            $query = $dbo->getQuery(true)
                ->select('f.*')
                ->from('#__lovefactory_fields f')
                ->where('f.id IN (' . implode(',', $array) . ')');
            $results = $dbo->setQuery($query)
                ->loadObjectList('id');

            // Set field mappings as required on the registration page.
            if ('registration' == $this->page) {
                $settings = LoveFactoryApplication::getInstance()->getSettings();
                $required = array(
                    $settings->registration_fields_mapping_username,
                    $settings->registration_fields_mapping_email,
                    $settings->registration_fields_mapping_password,
                    $settings->registration_fields_mapping_name,
                );
            }

            foreach ($results as $table) {
                if ('registration' == $this->page && in_array($table->id, $required)) {
                    $table->required = true;
                }

                $field = LoveFactoryField::getInstance($table->type, $table, $this->mode);

                $field->setFormControl($this->getFormControl());
                $field->setPage($this);

                $this->fields[$table->id] = $field;
            }
        }

        return $this->fields;
    }

    protected function getTablePage($page)
    {
        if (is_null($this->tablePage)) {
            $table = JTable::getInstance('Page', 'Table');
            $table->load(array('type' => $page));

            $this->tablePage = $table;
        }

        return $this->tablePage;
    }

    protected function getZoneTitles($titles)
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        foreach ($titles as $i => $title) {
            if (isset($this->titlePlaceholders[$title])) {
                $title = $this->titlePlaceholders[$title];
            } else {
                switch ($title) {
                    case '%%username%%':
                    case '%%display_name%%':
                        if (isset($this->data->display_name) && isset($this->data->user_id)) {
                            if ('profile_map' == $this->page) {
                                $target = $settings->members_map_profile_new_link ? 'target="_blank"' : '';
                            }
                            else {
                                $target = $settings->profile_link_new_window ? 'target="_blank"' : '';
                            }

                            $url = FactoryRoute::view('profile&user_id=' . $this->data->user_id);

                            if (JFactory::getApplication()->isAdmin()) {
                                $url = str_replace('/administrator', '', $url);
                            }

                            $title = '<a href="' . $url . '" ' . $target . '><i class="factory-icon icon-user"></i>' . $this->data->display_name . '</a>';
                        }
                        else {
                            $url = FactoryRoute::view('profile&user_id=' . $this->data->user_id);

                            if (JFactory::getApplication()->isAdmin()) {
                                $url = str_replace('/administrator', '', $url);
                            }

                            $title = $title = '<a href="' . $url . '" ' . $this->data->username . '><i class="factory-icon icon-user"></i>' . $this->data->username . '</a>';
                        }
                        break;
                }
            }

            $titles[$i] = $title;
        }

        $titles = new JRegistry($titles);

        return $titles;
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

    public function getSettings()
    {
        return $this->settings;
    }

    public function removeGenderFields()
    {
        foreach ($this->getAllFields() as $i => $field) {
            if (in_array($field->getType(), array('Gender', 'Looking'))) {
                unset($this->fields[$i]);
            }
        }
    }
}

abstract class LoveFactoryFieldSingleChoiceInterface
{
    public static function renderInputView($choices, $data)
    {
        if (!isset($choices[$data])) {
            return LoveFactoryField::renderInputBlank();
        }

        return $choices[$data];
    }

    public static function filterData($data, $choices)
    {
        if (!isset($choices[$data])) {
            return null;
        }

        return $data;
    }

    public static function getData($choices, $data)
    {
        return $choices[$data];
    }

    public static function getQuerySearchCondition($query, $id, $data)
    {
        if ('' === (string)$data) {
            return false;
        }

        $output = $query->quoteName('p.' . $id) . ' = ' . $query->quote($data);

        return $output;
    }

    public static function renderView($choices, $data, array $config = array())
    {
        if (!isset($choices[$data])) {
            return LoveFactoryField::renderInputBlank();
        }

        $html = array();

        if (isset($config['images']->choices[$data]) && $config['images']->choices[$data]) {
            $html[] = '<img src="' . JUri::root() . 'media/com_lovefactory/storage/fieldimage/' . $config['id'] . '/' . $data . '.png" /><br />';
        }

        $html[] = $choices[$data];

        return implode($html);
    }

    public static function renderEditSelect($choices, $name, $data, $id, $blank = 1)
    {
        if ($blank) {
            $choices = array('' => '') + $choices;
        }

        return JHtml::_('select.genericlist', $choices, $name, '', '', '', $data, $id);
    }

    public static function renderEditRadio($choices, $name, $data, $id, $config = array())
    {
        if (!isset($config['mode'])) {
            $config['mode'] = 'row';
        }

        $html = array();

        $data = is_numeric($data) ? intval($data) : '';

        if (!isset($config['id'])) {
            $config['id'] = 0;
        }

        if (!isset($config['images'])) {
            $config['images'] = array();
        }

        $html[] = '<div class="lovefactory-field-radio display-' . $config['mode'] . '">';
        foreach ($choices as $value => $choice) {
            $hasImage = isset($config['images']->choices[$value]) && $config['images']->choices[$value];
            $checked = $value === $data && !is_null($data) ? 'checked="checked"' : '';

            $html[] = '<label for="' . $id . '_' . $value . '">';

            $html[] = '<input type="radio" id="' . $id . '_' . $value . '" value="' . $value . '" name="' . $name . '" ' . $checked . ' style="float: left;" />';

            if ($hasImage) {
                $html[] = '<div style="float: left;">';
                $html[] = '<img src="' . JUri::root() . 'media/com_lovefactory/storage/fieldimage/' . $config['id'] . '/' . $value . '.png" /><br />';
            }

            $html[] = $choice;

            if ($hasImage) {
                $html[] = '</div>';
                $html[] = '<div style="clear: both;"></div>';
            }

            $br = $hasImage ? '' : '<br />';

            $html[] = ('row' == $config['mode'] ? $br : ',');
            $html[] = '</label>';
        }
        $html[] = '</div>';

        return implode($html);
    }

    public static function getDisplayData($choices, $data)
    {
        if (!isset($choices[$data])) {
            return null;
        }

        return $choices[$data];
    }
}

abstract class LoveFactoryFieldMultipleChoiceInterface
{
    public static function renderInputView($choices, $data)
    {
        $html = array();

        if (!is_array($data)) {
            $data = explode('/', trim($data, '/'));
        }

        foreach ($data as $key) {
            if (isset($choices[$key])) {
                $html[] = $choices[$key];
            }
        }

        if (!$html) {
            return LoveFactoryField::renderInputBlank();
        }

        return implode(', ', $html);
    }

    public static function filterData($data, $choices)
    {
        if (!is_array($data)) {
            return null;
        }

        JArrayHelper::toInteger($data);
        $data = array_intersect($data, array_keys($choices));

        if (!$data) {
            return null;
        }

        return $data;
    }

    public static function validate($title, $data, $minChoices, $maxChoices)
    {
        if ($minChoices && count($data) < $minChoices) {
            return sprintf('At least %d choices must be selected for field &quot;%s&quot;!', $minChoices, $title);
        }

        if ($maxChoices && count($data) > $maxChoices) {
            return sprintf('At most %d choices must be selected for field &quot;%s&quot;!', $maxChoices, $title);
        }

        return true;
    }

    public static function convertDataToProfile($data)
    {
        JArrayHelper::toInteger($data);

        return '/' . implode('/', $data) . '/';
    }

    public static function getQuerySearchCondition($query, $id, $data, $params)
    {
        if (is_null($data) || !is_array($data) || !$data) {
            return false;
        }

        $array = array();
        foreach ($data as $value) {
            if ($params->get('search_mode_multiple', 0)) {
                $array[] = $query->quoteName('p.' . $id) . ' = ' . $query->quote($value);
            } else {
                $array[] = $query->quoteName('p.' . $id) . ' LIKE ' . $query->quote('%/' . $value . '/%');
            }
        }

        $operand = $params->get('query_operand', 0) ? ' AND ' : ' OR ';
        $output = '(' . implode($operand, $array) . ')';

        return $output;
    }

    public static function renderView($choices, $data, $mode = 'line', array $config = array())
    {
        $html = array();

        if (!is_array($data)) {
            $data = explode('/', trim($data, '/'));
        }

        foreach ($data as $key) {
            if (isset($choices[$key])) {
                if (isset($config['images']->choices[$key]) && $config['images']->choices[$key]) {
                    $html[] = '<img src="' . JUri::root() . 'media/com_lovefactory/storage/fieldimage/' . $config['id'] . '/' . $key . '.png" />';
                }

                $html[] = $choices[$key];
            }
        }

        if (!$html) {
            return LoveFactoryField::renderInputBlank();
        }

        $separator = 'row' == $mode ? '<br />' : ', ';

        return implode($separator, $html);
    }

    public static function renderEditSelectMultiple($choices, $name, $data, $id, $config = array())
    {
        if (!is_array($data)) {
            $data = explode('/', trim($data, '/'));
        }

        $html = array();

        $html[] = JHtml::_('select.genericlist', $choices, $name . '[]', 'multiple="multiple"', '', '', $data, $id);

        $html[] = self::renderInfo($config);

        return implode("\n", $html);
    }

    public static function renderEditCheckbox($choices, $name, $data, $id, $required = array(), $config = array())
    {
        $html = array();

        if (!isset($config['mode'])) {
            $config['mode'] = 'row';
        }

        if (!is_array($data)) {
            $data = trim($data, '/');
            $data = '' == $data ? array() : explode('/', $data);
        }

        if (!isset($config['images'])) {
            $config['images'] = array();
        }

        $html[] = '<div class="lovefactory-field-checkbox display-' . $config['mode'] . '">';

        foreach ($choices as $value => $choice) {
            $hasImage = isset($config['images']->choices[$value]) && $config['images']->choices[$value];

            $checked = in_array($value, $data) ? 'checked="checked"' : '';
            $req = in_array($value, $required) ? '&nbsp;<span class="required">*</span>' : '';

            $html[] = '<label class="checkbox" for="' . $id . '_' . $value . '">';

            $html[] = '<input type="checkbox" id="' . $id . '_' . $value . '" value="' . $value . '" name="' . $name . '[]" ' . $checked . ' />';

            if ($hasImage) {
                $html[] = '<div style="float: left;">';
                $html[] = '<img src="' . JUri::root() . 'media/com_lovefactory/storage/fieldimage/' . $config['id'] . '/' . $value . '.png" /><br />';
            }

            $html[] = $choice . $req;

            if ($hasImage) {
                $html[] = '</div>';
                $html[] = '<div style="clear: both;"></div>';
            }

            $html[] = ('row' == $config['mode'] ? '' : ',');
            $html[] = '</label>';
        }
        $html[] = '</div>';

        $html[] = self::renderInfo($config);

        return implode($html);
    }

    public static function getDisplayData($choices, $data)
    {
        $html = array();

        if (!is_array($data)) {
            $data = explode('/', trim($data, '/'));
        }

        foreach ($data as $key) {
            if (isset($choices[$key])) {
                $html[] = $choices[$key];
            }
        }

        if (!$html) {
            return null;
        }

        return $html;
    }

    protected static function renderInfo($config = array())
    {
        $html = array();

        if (isset($config['mode']) && 'search' == $config['mode']) {
            return '';
        }

        if (isset($config['min_choices']) && $config['min_choices'] > 1) {
            $html[] = '<div class="lovefactory-field-info">' . FactoryText::sprintf('field_selectmulutile_min_choices', $config['min_choices']) . '</div>';
        }

        if (isset($config['max_choices']) && $config['max_choices'] > 1) {
            $html[] = '<div class="lovefactory-field-info">' . FactoryText::sprintf('field_selectmulutile_max_choices', $config['max_choices']) . '</div>';
        }

        return implode("\n", $html);
    }
}

class LoveFactoryReCaptcha
{
    protected $publicKey;
    protected $privateKey;

    public function __construct($keys)
    {
        $this->setPublicKey($keys[0]);
        $this->setPrivateKey($keys[1]);
    }

    public static function getInstance($keys)
    {
        static $instance = null;

        if (is_null($instance)) {
            require_once(JPATH_COMPONENT_SITE . DS . 'lib' . DS . 'recaptcha' . DS . 'recaptchalib.php');
            $instance = new self($keys);
        }

        return $instance;
    }

    public function setPublicKey($key)
    {
        $this->publicKey = $key;
    }

    public function setPrivateKey($key)
    {
        $this->privateKey = $key;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function render($theme = 'custom')
    {
        if ('custom' != $theme) {
            return $this->renderTheme($theme);
        }

        return $this->renderCustom();
    }

    public function checkAnswer($ip, $challange, $response)
    {
        $response = recaptcha_check_answer($this->getPrivateKey(), $ip, $challange, $response);

        return $response;
    }

    protected function renderTheme($theme)
    {
        $theme = '<script type="text/javascript">var RecaptchaOptions = { theme : \'' . $theme . '\' };</script>';

        $output = $theme . recaptcha_get_html($this->getPublicKey(), null, JUri::getInstance()->isSSL());

        return $output;
    }

    protected function renderCustom()
    {
        if (JUri::getInstance()->isSSL()) {
            $server = RECAPTCHA_API_SERVER;
        } else {
            $server = RECAPTCHA_API_SECURE_SERVER;
        }

        // TODO FACTORY: Style custom theme to mathc Love Factory
        $html = array();

        $html[] = '<script type="text/javascript">var RecaptchaOptions = {theme: \'custom\', custom_theme_widget: \'recaptcha_widget\'};</script>';

        $html[] = '<div id="recaptcha_widget" style="display:none">';

        $html[] = '<div id="recaptcha_image"></div>';
        $html[] = '<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>';

        $html[] = '<span class="recaptcha_only_if_image">Enter the words above:</span>';
        $html[] = '<span class="recaptcha_only_if_audio">Enter the numbers you hear:</span>';

        $html[] = '<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />';

        $html[] = '<div><a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a></div>';
        $html[] = '<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type(\'audio\')">Get an audio CAPTCHA</a></div>';
        $html[] = '<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type(\'image\')">Get an image CAPTCHA</a></div>';

        $html[] = '<div><a href="javascript:Recaptcha.showhelp()">Help</a></div>';

        $html[] = '</div>';

        $html[] = '<script type="text/javascript"';
        $html[] = 'src="' . $server . '/api/challenge?k=' . $this->getPublicKey() . '">';
        $html[] = '</script>';
        $html[] = '<noscript>';
        $html[] = '<iframe src="' . $server . '/api/noscript?k=' . $this->getPublicKey() . '"';
        $html[] = 'height="300" width="500" frameborder="0"></iframe><br>';
        $html[] = '<textarea name="recaptcha_challenge_field" rows="3" cols="40">';
        $html[] = '</textarea>';
        $html[] = '<input type="hidden" name="recaptcha_response_field"';
        $html[] = 'value="manual_challenge">';
        $html[] = '</noscript>';

        return implode("\n", $html);
    }
}
