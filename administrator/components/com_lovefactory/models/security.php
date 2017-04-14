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

class BackendModelSecurity extends JModelLegacy
{
    public function getSections()
    {
        jimport('joomla.filesystem.folder');

        $files = JFolder::files(
            JPATH_ADMINISTRATOR . '/components/com_lovefactory/models/firewall',
            '.xml',
            false,
            true
        );

        $sections = array();

        foreach ($files as $file) {
            $xml = simplexml_load_file($file);

            $section = $xml->xpath('//section');
            $name = (string)$section[0]->attributes()->name;

            $assets = $xml->xpath('//section/asset');
            foreach ($assets as $asset) {
                $type = (string)$asset->attributes()->type;
                $asset = (string)$asset;

                $sections[$name]['assets'][$type][] = $asset;
            }

            $sections[$name]['fixedRules'] = array();

            $fixedRules = $xml->xpath('//section/fixed/rule');
            foreach ($fixedRules as $fixedRule) {
                $sections[$name]['fixedRules'][] = (string)$fixedRule;
            }

            $sections[$name]['text'] = array(
                'label' => (string)$section[0]->attributes()->label
            );
        }

        return $sections;
    }

    public function getRestrictions()
    {
        $params = JComponentHelper::getParams('com_lovefactory');

        return (array)$params->get('firewall.data');
    }

    public function getRules()
    {
        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';

        $files = JFolder::files(JPATH_SITE . '/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Security/Rules', '.php');
        $rules = array();

        foreach ($files as $file) {
            $rule = pathinfo($file, PATHINFO_FILENAME);
            $name = '\\ThePhpFactory\\LoveFactory\\Security\\Rules\\' . $rule;

            $rClass = new ReflectionClass($name);

            if (!$rClass->isInstantiable()) {
                continue;
            }

            $class = new $name;

            $rules[] = $class->getName();
        }

        return $rules;
    }

    public function update(array $data = array())
    {
        $rules = array();
        $sections = $this->getSections();

        foreach ($data as $section => $restrictions) {
            foreach ($sections[$section]['assets'] as $type => $assets) {
                foreach ($assets as $asset) {
                    if (!isset($rules[$type])) {
                        $rules[$type] = array();
                    }

                    if (!isset($rules[$type][$asset])) {
                        $rules[$type][$asset] = array();
                    }

                    $rules[$type][$asset] = array_merge($rules[$type][$asset], $restrictions);
                }
            }
        }

        $params = JComponentHelper::getParams('com_lovefactory');
        $registry = new \Joomla\Registry\Registry(array(
            'firewall' => array(
                'rules' => $rules,
                'data' => $data,
            )));
        $params->merge($registry);

        $extension = JTable::getInstance('Extension');
        $extension->load(array('type' => 'component', 'element' => 'com_lovefactory'));
        $extension->params = $params->toString();

        return $extension->store();
    }
}
