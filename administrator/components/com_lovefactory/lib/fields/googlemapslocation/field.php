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

class LoveFactoryFieldGoogleMapsLocation extends LoveFactoryField
{
    protected $generatesDataColumn = true;
    protected $generatesVisibilityColumn = true;
    protected $accessPageBlackList = array('profile_map');

    public function renderInputEdit()
    {
        $maps = $this->getMaps();
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (is_object($this->data)) {
            $this->data = (array)$this->data;
        }

        $center = array();
        $center['lat'] = !is_null($this->data['lat']) ? $this->data['lat'] : $settings->gmaps_default_x;
        $center['lng'] = !is_null($this->data['lng']) ? $this->data['lng'] : $settings->gmaps_default_y;
        $center['zoom'] = !is_null($this->data['zoom']) ? $this->data['zoom'] : $settings->gmaps_default_z;

        $html = array();

        $html[] = $maps->renderMap($this->getHtmlId(), $center, $this->params->get('edit_height', 200), $this->params->get('edit_width', '100%'));

        FactoryHtml::script('fields/googlemapslocation');

        $options = array();
        $options['id'] = $this->getHtmlId();

        if (!isset($this->data['lat']) || !isset($this->data['lng']) || is_null($this->data['lat']) || is_null($this->data['lng'])) {
            $options['position'] = null;
        } else {
            $options['position'] = array('lat' => $this->data['lat'], 'lng' => $this->data['lng']);
        }

        $document = JFactory::getDocument();
        $document->addScriptDeclaration('jQuery(document).ready(function ($) { $.LoveFactoryFieldGoogleMapsLocation("renderInputEdit", ' . json_encode($options) . '); });');

        $html[] = '<a href="#" id="' . $this->getHtmlId() . '_remove"><span class="fa fa-fw fa-map-marker"></span>' . FactoryText::_('field_googlemaps_remove_location_label') . '</a>';

        $disabled = $options['position'] ? '' : 'disabled="disabled"';

        $html[] = '<input type="hidden" ' . $disabled . ' name="' . $this->getHtmlName() . '[lat]" id="' . $this->getHtmlId() . '_lat" value="' . $this->data['lat'] . '" />';
        $html[] = '<input type="hidden" ' . $disabled . ' name="' . $this->getHtmlName() . '[lng]" id="' . $this->getHtmlId() . '_lng" value="' . $this->data['lng'] . '" />';
        $html[] = '<input type="hidden" ' . $disabled . ' name="' . $this->getHtmlName() . '[zoom]" id="' . $this->getHtmlId() . '_zoom" value="' . ($this->data['zoom'] ? $this->data['zoom'] : $center['zoom']) . '" />';

        return implode("\n", $html);
    }

    public function renderInputView()
    {
        $maps = $this->getMaps();

        if (is_null($this->data)) {
            return FactoryText::_('field_googlemaps_no_location_defined');
        }

        $id = $this->getHtmlId() . $this->userId;

        $html = array();

        $html[] = $maps->renderMap($id, $this->data, $this->params->get('view_height', 200), $this->params->get('view_width', '100%'));

        FactoryHtml::script('fields/googlemapslocation');

        if (is_object($this->data)) {
            $this->data = (array)$this->data;
        }

        $options = array();
        $options['id'] = $id;
        $options['lat'] = $this->data['lat'];
        $options['lng'] = $this->data['lng'];

        $document = JFactory::getDocument();
        $document->addScriptDeclaration('jQuery(document).ready(function ($) { $.LoveFactoryFieldGoogleMapsLocation("renderInputView", ' . json_encode($options) . '); });');

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

        if (is_null($this->data['lat']) ||
            is_null($this->data['lng']) ||
            is_null($this->data['zoom']) ||
            intval($this->data['zoom']) < 0 ||
            intval($this->data['zoom']) > 21
        ) {
            $this->setError(FactoryText::sprintf('field_googlemapslocation_error_invalid_option', $this->getLabel()));
            return false;
        }

        return true;
    }

    public function bind($data)
    {
        parent::bind($data);

        if (is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data[$this->getId() . '_lat'])) {
            $this->data['lat'] = $data[$this->getId() . '_lat'];
        }

        if (isset($data[$this->getId() . '_lng'])) {
            $this->data['lng'] = $data[$this->getId() . '_lng'];
        }

        if (isset($data[$this->getId() . '_zoom'])) {
            $this->data['zoom'] = $data[$this->getId() . '_zoom'];
        }
    }

    public function bindDataToProfile($profile)
    {
        $profile[$this->getId() . '_lat'] = $this->data['lat'];
        $profile[$this->getId() . '_lng'] = $this->data['lng'];
        $profile[$this->getId() . '_zoom'] = $this->data['zoom'];

        $profile[$this->getVisibilityId()] = $this->getVisibility();

        return $profile;
    }

    public function filterData()
    {
        if (!is_array($this->data)) {
            $this->data = null;

            return true;
        }

        if (!isset($this->data['lat'])) {
            $this->data['lat'] = null;
        }

        if (!isset($this->data['lng'])) {
            $this->data['lng'] = null;
        }

        if (!isset($this->data['zoom'])) {
            $this->data['zoom'] = null;
        }
    }

    public function isRenderable()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if (!$settings->enable_gmaps) {
            return false;
        }

        return parent::isRenderable();
    }

    public function getQueryAlterProfileTableInsertColumn($dbo)
    {
        $query = ' ALTER TABLE #__lovefactory_profiles'
            . ' ADD COLUMN ' . $dbo->quoteName($this->getId() . '_lat') . ' DECIMAL(15,12),'
            . ' ADD COLUMN ' . $dbo->quoteName($this->getId() . '_lng') . ' DECIMAL(15,12),'
            . ' ADD COLUMN ' . $dbo->quoteName($this->getId() . '_zoom') . ' TINYINT(1)';

        return $query;
    }

    public function getQueryAlterProfileTableDropColumn($dbo)
    {
        $query = ' ALTER TABLE #__lovefactory_profiles'
            . ' DROP COLUMN ' . $dbo->quoteName($this->getId() . '_lat') . ','
            . ' DROP COLUMN ' . $dbo->quoteName($this->getId() . '_lng') . ','
            . ' DROP COLUMN ' . $dbo->quoteName($this->getId() . '_zoom');

        return $query;
    }

    public function getProfileTableColumnName()
    {
        return $this->getId() . '_lat';
    }

    /**
     * @return LoveFactoryGoogleMaps
     */
    protected function getMaps()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        return LoveFactoryGoogleMaps::getInstance($settings->gmaps_api_key);
    }
}
