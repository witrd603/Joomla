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

class LoveFactoryFieldPhotos extends LoveFactoryField
{
    protected $accessPageWhiteList = array('profile_results', 'profile_view', 'friends_view', 'profile_map', 'search_quick', 'search_advanced', 'radius_search');

    public function renderInputView()
    {
        $url = FactoryRoute::view('photos&user_id=' . $this->userId);

        if (JFactory::getApplication()->isAdmin()) {
            $url = str_replace('/administrator', '', $url);
        }

        return '<a href="' . $url . '">' . $this->data . '</a>';
    }

    public function renderInputSearch()
    {
        $choices = array(1 => FactoryText::_('field_photos_search_checkbox_label'));
        $html = array();

        $html[] = LoveFactoryFieldMultipleChoiceInterface::renderEditCheckbox($choices, $this->getHtmlName(), $this->data, $this->getHtmlId());

        return implode("\n", $html);
    }

    public function getId()
    {
        return 'photos';
    }

    public function getQuerySearchCondition($query)
    {
        if (is_null($this->data) || !is_array($this->data) || !$this->data || !in_array(1, $this->data)) {
            return false;
        }

        $mode = $this->getParam('search_mode', 'gallery');

        if ('profile' == $mode) {
            return $query->quoteName('p.main_photo') . '  > ' . $query->quote(1);
        }

        $this->addTableLeftJoin($query);
        $query->group('p.user_id');

        return $query->quoteName('photos.id') . ' IS NOT NULL';
    }

    public function addQueryView($query)
    {
        $user = JFactory::getUser();
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $approved = $settings->approval_photos ? 'AND pt.approved = ' . $query->quote(1) : '';

        $query->select('(SELECT COUNT(pt.id) FROM #__lovefactory_photos pt WHERE (pt.user_id = p.user_id ' . $approved . ' AND (p.user_id = ' . $query->quote($user->id) . ' OR pt.status = 0 OR (pt.status = 1 AND f.id IS NOT NULL)))) AS photos');

//    $this->addTableLeftJoin($query);
//    $this->addQueryElement($query, 'select', 'COUNT(DISTINCT photos.id) AS photos');
    }

    protected function addTableLeftJoin($query, $alias = 'photos')
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        $approved = $settings->approval_photos ? 'AND ' . $alias . '.approved = ' . $query->quote(1) : '';

        $this->addQueryElement($query, 'join', '#__lovefactory_photos ' . $alias . ' ON (' . $alias . '.user_id = p.user_id ' . $approved . ' AND ( (' . $alias . '.status = 0) OR (' . $alias . '.status = 1 AND f.id IS NOT NULL) ))', 'leftjoin');
    }
}
