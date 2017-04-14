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

class BackendModelConfiguration extends JModelLegacy
{
    public function getItems()
    {
        $path = JURI::root() . 'administrator/components/com_lovefactory/assets/images/configuration/';

        $items = array(
            'settings',
            'fields',
            'pages',
            '',
            'memberships',
            'security',
            '',
            'pricing',
            'gateways',
            'invoices' => array('link' => FactoryRoute::view('settings&layout=invoices')),
            '',
            'advertising' => array('link' => FactoryRoute::view('settings&layout=advertising')),
            'notifications',
            '',
            'backup' => array('link' => FactoryRoute::view('settings&layout=backup')),
            'imports',
        );

        foreach ($items as $item => $options) {
            if ('' == $options) {
                $buttons[] = '';
            } else {
                $key = is_string($options) ? $options : $item;
                if (is_string($options) || !isset($options['link'])) {
                    $link = FactoryRoute::view($options);
                } else {
                    $link = $options['link'];
                }

                $buttons[$item] = array(
                    'link' => $link,
                    'image' => $path . $key . '.png',
                    'text' => FactoryText::_('configuration_button_' . $key),
                    'access' => array(),
                );
            }
        }

        return $buttons;
    }

    public function getVersion()
    {
        jimport('joomla.filesystem.file');

        $file = JPATH_COMPONENT_ADMINISTRATOR . DS . 'lovefactory.xml';

        $data = JInstaller::parseXMLInstallFile($file);

        return $data['version'];
    }

    public function getGateways()
    {
        $dbo = $this->getDbo();
        $query = $dbo->getQuery(true)
            ->select('g.title')
            ->from('#__lovefactory_gateways g')
            ->where('g.published = ' . $dbo->quote(1));

        $results = $dbo->setQuery($query)
            ->loadObjectList('title');

        return array_keys($results);
    }
}
