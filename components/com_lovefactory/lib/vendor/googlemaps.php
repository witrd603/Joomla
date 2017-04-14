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

class LoveFactoryGoogleMaps
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public static function getInstance($apiKey)
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self($apiKey);
        }

        return $instance;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function geoCode($address)
    {
        jimport('joomla.filesyste.file');

        $address = urlencode($address);
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&sensor=false';

        $data = json_decode($this->getUrl($url));

        if ('OK' != $data->status || !isset($data->results[0]->geometry->location)) {
            return false;
        }

        return $data->results[0]->geometry->location;
    }

    public function renderMap($id = 'lovefactory-googlemap', $location = array(), $height = '400', $width = '100%', $config = array())
    {
        // Render Javascript.
        FactoryHtml::script('googlemaps');
        JHtml::script('https://maps.googleapis.com/maps/api/js?key=' . $this->getApiKey());

        JText::script('JPREVIOUS');
        JText::script('JNEXT');

        // Set default values for location.
        if (!is_array($location)) {
            $location = array();
        }

        if (!isset($location['lat']) || !isset($location['lng'])) {
            $location['lat'] = 48;
            $location['lng'] = 14;
        }

        if (!isset($location['zoom'])) {
            $location['zoom'] = 3;
        }

        // Set default values for dimension.
        if (false === strpos($height, '%')) {
            $height .= 'px';
        }

        if (false === strpos($width, '%')) {
            $width .= 'px';
        }

        $options = '{ center: new google.maps.LatLng(' . $location['lat'] . ', ' . $location['lng'] . '), zoom: ' . $location['zoom'] . ' }';
        $document = JFactory::getDocument();
        $initJs = '$("#' . $id . '").LoveFactoryGoogleMap(' . $options . ') ';
        $js = array();

        $js[] = 'jQuery(document).ready(function ($) { ';

        if (isset($config['initOnTabOpen'])) {
            $tab = $config['initOnTabOpen'];
            $js[] = 'var tab = $(".nav li a[href=\"#' . $tab . '\"]");';
            $js[] = 'if (tab.parent().hasClass("active")) {';
            $js[] = $initJs;
            $js[] = '}';
            $js[] = 'else {';
            $js[] = '  $(".nav li a[href=\"#' . $tab . '\"]").click(function (event) { ';
            $js[] = $initJs;
            $js[] = '  });';
            $js[] = '}';
        } else {
            $js[] = $initJs;
        }

        $js[] = ' });';

        $document->addScriptDeclaration(implode("\n", $js));

        $html = array();

        $html[] = '<div id="' . $id . '" style="height: ' . $height . '; width: ' . $width . ';" class="lovefactory-google-map"></div>';

        return implode("\n", $html);
    }

    public function renderJavascript()
    {
        FactoryHtml::script('googlemaps');
        JHtml::script('https://maps.googleapis.com/maps/api/js?key=' . $this->getApiKey());
    }

    protected function getUrl($url)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $output = curl_exec($ch);

            if (!curl_errno($ch)) {
                curl_close($ch);
                return $output;
            }

            curl_close($ch);
        }

        return file_get_contents($url);
    }
}
