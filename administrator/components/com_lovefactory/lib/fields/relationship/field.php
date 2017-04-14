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

class LoveFactoryFieldRelationship extends LoveFactoryField
{
    protected $accessPageWhiteList = array('profile_results', 'profile_view', 'friends_view', 'profile_map');

    public function renderInputView()
    {
        if (!$this->data) {
            return FactoryText::_('field_relationship_no_relationship');
        }

        $html = array();

        if ('photo' == $this->getParam('display_mode', 'photo')) {
            $photo = $this->getProfilePhoto($this->data);

            $html = array();

            $html[] = '<div style="background-image: url(\'' . $photo . '\');" class="lovefactory-thumbnail">';
            $html[] = '</div>';
        }

        $html[] = '<a href="' . FactoryRoute::view('profile&user_id=' . $this->data) . '"><i class="factory-icon icon-user"></i>' . JFactory::getUser($this->data)->username . '</a>';

        return implode("\n", $html);
    }

    public function renderInputSearch()
    {
        $text = $this->getMode() ? 'field_online_search_checkbox_in_relationship_label' : 'field_online_search_checkbox_not_in_relationship_label';

        $choices = array(1 => FactoryText::_($text));
        $html = array();

        $html[] = LoveFactoryFieldMultipleChoiceInterface::renderEditCheckbox($choices, $this->getHtmlName(), $this->data, $this->getHtmlId());

        return implode("\n", $html);
    }

    public function getId()
    {
        return 'relationship';
    }

    public function getQuerySearchCondition($query)
    {
        if (is_null($this->data) || !is_array($this->data) || !$this->data || !in_array(1, $this->data)) {
            return false;
        }

        $operand = $this->getMode() ? ' <> ' : ' = ';
        $output = $query->quoteName('p.' . $this->getId()) . $operand . $query->quote(0);

        return $output;
    }

    public function getMode($default = 1)
    {
        return $this->params->get('search_mode', $default);
    }

    protected function getProfilePhoto($userId)
    {
        $profile = $this->getProfile($userId);

        return $profile->getProfilePhotoSource(true);
    }

    protected function getProfile($userId)
    {
        /* @var $profile TableProfile */
        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($userId);

        return $profile;
    }
}
