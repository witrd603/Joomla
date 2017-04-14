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

class LoveFactoryRouterBuilder
{
    private $segments;
    private $firstSegmentLang;
    private $cachedProfileParts = array();
    private $cachedFields = null;

    public function __construct(array $segments = array(), $firstSegmentLang = false)
    {
        $this->segments = $segments;
        $this->firstSegmentLang = $firstSegmentLang;
    }

    public function build(JRouterSite $router, JUri &$uri)
    {
        if (!$this->assertValidConditions($uri)) {
            return false;
        }

        /** @var integer $userId */
        $userId = $uri->getVar('user_id', JFactory::getUser()->id);
        $query = $this->buildQuery($uri);
        $fields = $this->loadFields($this->segments);

        $parts = $this->getProfileParts($userId, $this->segments, $fields);

        $uri->setQuery(null);
        $uri->setPath('index.php/' . implode('/', $parts) . $query);

        return true;
    }

    private function loadFields(array $segments = array())
    {
        if (null === $this->cachedFields) {
            $this->cachedFields = array();

            if ($segments) {
                $dbo = JFactory::getDbo();

                $query = $dbo->getQuery(true)
                    ->select('f.*')
                    ->from($dbo->qn('#__lovefactory_fields', 'f'))
                    ->where('f.id IN (' . implode(',', $dbo->q($segments)) . ')');

                $this->cachedFields = $dbo->setQuery($query)
                    ->loadObjectList('id');
            }
        }

        return $this->cachedFields;
    }

    private function getProfileParts($userId, array $segments = array(), array $fields = array())
    {
        if (!isset($this->cachedProfileParts[$userId])) {
            $profile = JTable::getInstance('Profile', 'Table');
            $profile->load($userId);

            $parts = array();
            $languageFilterPluginEnabled = JPluginHelper::isEnabled('system', 'languagefilter');

            if ($languageFilterPluginEnabled && $this->firstSegmentLang) {
                foreach (JLanguageHelper::getLanguages() as $lang) {
                    if ($lang->lang_code === JFactory::getLanguage()->getTag()) {
                        $parts[] = $lang->sef;
                    }
                }
            }

            foreach ($segments as $id) {
                $field = LoveFactoryField::getInstance($fields[$id]->type, $fields[$id]);
                $field->bind($profile);

                $data = $field->getDisplayData();

                if (is_array($data)) {
                    $data = implode(',', $data);
                }

                $part = LoveFactoryRouterHelper::stringURLSafe($data);
                $parts[] = '' === $part ? '-' : $part;
            }

            $user = JFactory::getUser($userId);
            $parts[] = $user->username;

            $this->cachedProfileParts[$userId] = $parts;
        }

        return $this->cachedProfileParts[$userId];
    }

    private function buildQuery(JUri $uri)
    {
        $limitstart = $uri->getVar('limitstart');
        $format = $uri->getVar('format', 'html');
        $query = array();

        if (null !== $limitstart) {
            $query[] = 'limitstart=' . $limitstart;
        }

        if ('html' != $format) {
            $query[] = 'format=' . $format;
        }

        $query = $query ? '?' . implode('&', $query) : '';

        return $query;
    }

    private function assertValidConditions(JUri $uri)
    {
        // It's not a Love Factory link.
        if ('com_lovefactory' !== $uri->getVar('option')) {
            return false;
        }

        // The link is just Itemid based.
        if (false !== strpos($uri->toString(), 'index.php?Itemid=')) {
            return false;
        }

        // It's not a profile view link.
        if (!in_array($uri->getVar('view'), array('profile'))) {
            return false;
        }

        return true;
    }
}
