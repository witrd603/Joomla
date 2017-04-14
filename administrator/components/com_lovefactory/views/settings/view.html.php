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

jimport('joomla.application.component.view');
jimport('joomla.html.pane');

class BackendViewSettings extends LoveFactoryAdminView
{
    protected $form;

    function display($tpl = null)
    {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        $settings = new LovefactorySettings();
        $admins = $this->get('Admins');
        $access = $this->get('Access');
        //$pane            = JPane::getInstance('tabs', array('startOffset' => $selected_tab));
        $shoutbox_log = $this->get('ShoutboxLogSize');
        $writable = $this->get('SettingsFileIsWritable');
        $chatfactory = $this->get('ChatFactory');
        $banned_words = $this->get('BannedWords');
        $location_fields = $this->get('LocationFields');
        $redirect_items = $this->get('RedirectItems');
        $this->editor = $this->get('Editor');

        $this->gmaps_fields = $this->get('GoogleMapsFields');
        $this->fields_username = $this->get('UsernameFields');
        $this->fields_email = $this->get('EmailFields');
        $this->fields_password = $this->get('PasswordFields');
        $this->fields_name = $this->get('NameFields');

        $this->pluginsStatus = $this->get('PluginsStatus');

        $this->blogfactory = $this->get('BlogFactory');

        $this->settings = $settings;
        $this->admins = $admins;
        $this->access = $access;
        $this->shoutbox_log = $shoutbox_log;
        $this->writable = $writable;
        $this->chatfactory = $chatfactory;
        $this->banned_words = $banned_words;
        $this->location_fields = $location_fields;
        $this->redirect_items = $redirect_items;

        $this->notifications = $this->get('Notifications');
        $this->errorReporting = $this->get('ErrorReporting');

        $this->joomlaUserRegistrationDisabledNotification = false;
        if (1 === $settings->registration_mode) {
            $userSettings = JComponentHelper::getParams('com_users');
            if (!$userSettings->get('allowUserRegistration')) {
                $this->joomlaUserRegistrationDisabledNotification = true;
            }
        }

        JHtml::_('behavior.framework');
        JHtml::_('jquery.framework');

        $key = LoveFactoryApplication::getInstance()->getSettings('gmaps_api_key', '');
        if ($key) {
            JHtml::script('https://maps.googleapis.com/maps/api/js?key=' . $key);
        }

        if ($settings->enable_gmaps && $settings->gmaps_api_key) {
            $googleMaps = LoveFactoryGoogleMaps::getInstance($settings->gmaps_api_key);
            $googleMaps->renderJavascript();
        }

        JText::script('COM_LOVEFACTORY_SETTINGS_INVOICE_BUYER_ADD_FIELDS');

        FactoryHtml::script('views/backend/settings');
        JLoader::register('JHtmlSettings', JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'settings.php');

        JHtml::stylesheet('administrator/components/com_lovefactory/assets/css/main.css');

        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'html.php');

        $this->app = LoveFactoryApplication::getInstance();
        $this->activeTab = $this->get('ActiveTab');

        $this->prepareProfileSettings();

        $this->form = $this->getSettingsForm();

        parent::display($tpl);
    }

    protected function prepareProfileSettings()
    {
        $settings = JComponentHelper::getParams('com_lovefactory');

        JFactory::getLanguage()->load('com_lovefactory', JPATH_SITE);

        JForm::addFieldPath(JPATH_SITE . '/components/com_lovefactory/models/fields');
        JForm::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models/fields');

        $form = JForm::getInstance(
            'com_lovefactory.settings.profile.settings',
            JPATH_SITE . '/components/com_lovefactory/models/forms/settings.xml',
            array('control' => 'settings[profile_settings]')
        );

        $xml = array();
        $xml[] = '<form>';

        foreach ($form->getFieldsets() as $fieldset) {
            foreach ($form->getFieldset($fieldset->name) as $field) {
                $xml[] = '<fieldset name="enable">';

                $xml[] = '<fields name="enable">';

                if ($field->group) {
                    foreach (explode('.', $field->group) as $group) {
                        $xml[] = '<fields name="' . $group . '">';
                    }
                }

                $xml[] = '<field name="' . $field->fieldname . '" type="LoveFactoryBoolean" />';

                if ($field->group) {
                    foreach (explode('.', $field->group) as $group) {
                        $xml[] = '</fields>';
                    }
                }

                $xml[] = '</fields>';

                $xml[] = '</fieldset>';
            }
        }

        $xml[] = '</form>';

        $form->load(implode("\n", $xml));

        LoveFactoryHelper::addFormLabels($form);

        $form->bind($settings->get('profile_settings'));

        $this->forms['profile.settings'] = $form;
    }

    private function getSettingsForm()
    {
        JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models/forms');
        $form = JForm::getInstance('com_lovefactory.settings', 'settings', array(
            'control' => 'settings',
        ));

        $buttons = array(
            'interactions',
            'messages',
            'requests',
            'comments',
            'profile_view',
            'profile_update',
            'gallery',
            'friends',
        );

        foreach ($buttons as $button) {
            $form->load(
                <<<XML
                <form>
  <fieldset name="infobar">
    <fields name="infobar">
      <field name="button_{$button}_enabled" type="radio" class="btn-group btn-group-yesno" default="1" filter="integer" showon="enabled:1">
        <option value="1">JENABLED</option>
        <option value="0">JDISABLED</option>
      </field>

      <field name="button_{$button}_itemid" type="menuitem" filter="integer" default="0" showon="enabled:1[AND]button_{$button}_enabled:1">
        <option value="0">None</option>
      </field>

      <field name="button_{$button}_itemid_usage" type="list" default="append" showon="enabled:1[AND]button_{$button}_enabled:1">
        <option value="append">Append Itemid</option>
        <option value="replace">Use just Itemid</option>
      </field>
    </fields>
  </fieldset>
</form>
XML
            );
        }

        LoveFactoryHelper::addFormLabels($form);

        $form->bind(JComponentHelper::getParams('com_lovefactory'));

        return $form;
    }
}
