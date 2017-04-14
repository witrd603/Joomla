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

class LoveFactoryRouterParser
{
    CONST ROUTE_PROFILE = 'profile';
    CONST ROUTE_SEARCH = 'search';

    private $segments;
    private $menuItemCheck;
    private $cachedFields = array();

    public function __construct(array $segments = array(), $menuItemCheck = false)
    {
        $this->segments = $segments;
        $this->menuItemCheck = $menuItemCheck;
    }

    public function parse(JRouterSite $router, JUri &$uri)
    {
        jimport('joomla.filesystem.file');

        $path = $this->sanitizePath($uri->getPath());

        $languages = JLanguageHelper::getLanguages('sef');
        if (array_key_exists($path[0], $languages)) {
            unset($path[0]);
            $path = array_values($path);
        }

        $route = $this->sniffRoute($path, $this->segments);

        if (null === $route) {
            return array();
        }

        if (self::ROUTE_PROFILE === $route) {
            if (false !== $result = $this->parseProfileRoute($path)) {
                return $result;
            }
        }

        if (self::ROUTE_SEARCH === $route) {
            if (false !== $result = $this->parseSearchRoute($path)) {
                return $result;
            }
        }

        return array();
    }

    private function sanitizePath($path)
    {
        $path = explode('/', $path);

        if (JFactory::getApplication()->get('sef_suffix')) {
            $path[count($path) - 1] = JFile::stripExt(end($path));
        }

        return $path;
    }

    private function sniffRoute(array $path = array(), array $segments = array())
    {
        if ($this->menuItemCheck) {
            $menu = JFactory::getApplication()->getMenu();

            if ($menu->getItems('alias', $path[0])) {
                return null;
            }
        }

        if ((isset($path[0]) && 'component' === $path[0]) ||
            (isset($path[1]) && 'component' === $path[1])
        ) {
            return null;
        }

        if ($this->isProfileRoute($path, $segments)) {
            return self::ROUTE_PROFILE;
        }

        if ($this->isSearchRoute($path, $segments)) {
            return self::ROUTE_SEARCH;
        }

        return null;
    }

    private function isProfileRoute(array $path = array(), array $segments = array())
    {
        return count($segments) + 1 === count($path);
    }

    private function isSearchRoute(array $path = array(), array $segments = array())
    {
        return count($segments) >= count($path);
    }

    private function parseProfileRoute(array $path = array())
    {
        $username = end($path);

        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('u.id')
            ->from($dbo->qn('#__users', 'u'))
            ->where('u.username = ' . $dbo->q($username));
        $result = $dbo->setQuery($query)
            ->loadResult();

        if (!$result) {
            $result = -1;
        }

        return array(
            'option'  => 'com_lovefactory',
            'view'    => 'profile',
            'user_id' => $result,
        );
    }

    private function parseSearchRoute(array $path = array())
    {
        $array = array();

        foreach ($path as $i => $value) {
            if ('-' === $value) {
                continue;
            }

            $field = $this->loadField($this->segments[$i]);
            $found = false;

            switch ($field->getBaseType()) {
                case 'Radio':
                case 'Select':
                case 'Gender':
                    foreach ($field->getChoices() as $j => $choice) {
                        if (LoveFactoryRouterHelper::stringURLSafe($choice) === $value) {
                            $array[$field->getId()] = $j;
                            $found = true;
                        }
                    }
                    break;

                case 'SelectMultiple':
                case 'Checkbox':
                    $values = explode(',', $value);

                    foreach ($values as $val) {
                        foreach ($field->getChoices() as $j => $choice) {
                            if (LoveFactoryRouterHelper::stringURLSafe($choice) === $val) {
                                $array[$field->getId()][] = $j;
                                $found = true;
                            }
                        }
                    }
                    break;

                case 'Text':
                    $found = true;
                    $array[$field->getId()] = str_replace('-', '%', $value);
                    break;
            }

            if (!$found) {
                return false;
            }
        }

        return array(
            'option' => 'com_lovefactory',
            'view'   => 'fixedsearch',
            'form'   => $array,
        );
    }

    /**
     * @param integer $id
     * @return LoveFactoryField
     */
    private function loadField($id)
    {
        if (!isset($this->cachedFields[$id])) {
            $field = JTable::getInstance('Field', 'Table');
            $field->load($id);

            $field = LoveFactoryField::getInstance($field->type, $field);

            $this->cachedFields[$id] = $field;
        }

        return $this->cachedFields[$id];
    }
}
