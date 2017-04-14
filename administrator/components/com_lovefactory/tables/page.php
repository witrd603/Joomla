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

jimport('joomla.language.helper');

class TablePage extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__lovefactory_pages', 'id', $db);
    }

    function getZones($type_id)
    {
        $query = ' SELECT `fields`'
            . ' FROM #__lovefactory_pages'
            . ' WHERE type_id = ' . $type_id;
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    }

    function loadPageTypeId($type_id)
    {
        $query = ' SELECT id'
            . ' FROM #__lovefactory_pages'
            . ' WHERE type_id = ' . $type_id;
        $this->_db->setQuery($query);
        $id = $this->_db->loadResult();

        $lang = JFactory::getLanguage();

        $query = ' SELECT p.*,'
            . '   IF (t.id IS NOT NULL AND t.titles <> "", t.titles, p.titles) AS titles'
            . ' FROM #__lovefactory_pages p'
            . ' LEFT JOIN #__lovefactory_pages_translation t ON (t.page_id = ' . $id . ' AND t.lang_code = "' . $lang->getTag() . '")'
            . ' WHERE p.id = ' . $id;
        $this->_db->setQuery($query);
        $this->bind($this->_db->loadObject());

        return $this;
    }

    public function check()
    {
        if (!parent::check()) {
            return false;
        }

        $registry = new JRegistry($this->fields);
        $this->fields = $registry->toString();

        return true;
    }

    function loadTranslations()
    {
        if (is_null($this->id)) {
            $this->translations = array();
            return false;
        }

        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('t.*')
            ->from('#__lovefactory_pages_translation t')
            ->where('t.page_id = ' . $dbo->Quote($this->id));
        $dbo->setQuery($query);

        $translations = $dbo->loadObjectList('lang_code');
        $languages = JLanguageHelper::getLanguages();

        foreach ($translations as $i => $translation) {
            $titles = explode('###', $translation->titles);
            $array = array();

            foreach ($titles as $title) {
                $title = explode('___', $title);

                $array[$title[0]] = $title[1];
            }

            $translations[$i]->titles = $array;
        }

        $this->translations = $translations;

        return true;
    }

    function store($updateNulls = false)
    {
        if (!parent::store($updateNulls)) {
            return false;
        }

        $this->saveTranslations();

        return true;
    }

    protected function saveTranslations()
    {
        $settings = new LovefactorySettings();

        if (!$settings->show_translation_fields) {
            return false;
        }

        $translation = JFactory::getApplication()->input->get('translation', array(), 'array');
        $languages = JLanguageHelper::getLanguages();

        foreach ($languages as $language) {
            $table = JTable::getInstance('PageTranslation', 'Table');
            $table->find(array('page_id' => $this->id, 'lang_code' => $language->lang_code));

            $array = array();
            foreach ($translation[$language->lang_code]['title'] as $id => $title) {
                $array[] = $id . '___' . $title;
            }

            $table->titles = implode('###', $array);

            $table->store();
        }
    }

    public function loadByName($name)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('p.*')
            ->from($dbo->quoteName($this->getTableName()) . ' p')
            ->where('p.' . $dbo->quoteName('id') . ' = ' . $dbo->quote($this->getPageIdForName($name)));
        $dbo->setQuery($query);
        $result = $dbo->loadObject();

        return $this->bind($result);
    }

    public function getPageIdForName($name)
    {
        $names = array(
            'signup' => 1,
            'edit' => 2,
            'quicksearch' => 3,
            'advancedsearch' => 4,
            'result' => 5,
            'view' => 6,
            'friends' => 7,
            'fillin' => 8,
            'moreinfo' => 9,
        );

        return $names[$name];
    }
}
