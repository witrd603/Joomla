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

class LoveFactoryFieldUsername extends LoveFactoryField
{
    protected $accessPageBlackList = array('profile_edit', 'profile_fillin');

    public function renderInputView()
    {
        return $this->data;
    }

    public function renderInputEdit()
    {
        $data = htmlentities($this->data, ENT_COMPAT, 'UTF-8');

        $html = array();

        $html[] = '<input type="text" id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" class="lovefactory-field-username" value="' . $data . '" />';

        if ($this->params->get('ajax_check', 1)) {
            FactoryHtml::script('lovefactory');
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $("#' . $this->getHtmlId() . '").LoveFactoryFieldUsernameAjaxCheck(); });');

            $html[] = '<div class="error-username-exists" style="display: none;">' . FactoryText::_('signup_username_check_error') . '</div>';
        }

        return implode("\n", $html);
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        // Remove banned characters.
        if ('' !== $bannedCharacters = $this->getParams()->get('banned_characters', '')) {
            $bannedCharacters = str_split($bannedCharacters);
            $this->data = str_ireplace($bannedCharacters, '', $this->data);
        }

        if (preg_match("#[<>\"'%;()&]#i", $this->data) || strlen(utf8_decode($this->data)) < 2) {
            $this->setError(JText::sprintf('JLIB_DATABASE_ERROR_VALID_AZ09', 2));
            return false;
        }

        if (0 === strpos($this->data, '_')) {
            $this->setError(FactoryText::sprintf('field_username_error_underscore', $this->getLabel()));
            return false;
        }

        if ($this->checkUsernameExists($this->data)) {
            $this->setError(FactoryText::_('field_username_error_username_taken'));
            return false;
        }

        // Check if username is banned.
        if ('' !== $bannedUsernames = $this->getParams()->get('banned_usernames', '')) {
            $bannedUsernames = preg_split("/\r\n|\n|\r/", strtolower($bannedUsernames));

            if (in_array($this->data, $bannedUsernames)) {
                $this->setError(FactoryText::_('field_username_error_username_banned'));
                return false;
            }
        }

        return true;
    }

    public function getId()
    {
        return 'username';
    }

    public function getQuerySearchCondition($query)
    {
        if (is_null($this->data) || '' == $this->data) {
            return false;
        }

        $output = $query->quoteName('u.username') . ' LIKE ' . $query->quote('%' . $this->data . '%');

        return $output;
    }

    public function filterData()
    {
        $dbo = JFactory::getDbo();

        $this->data = $dbo->escape(trim($this->data));

        if ('' == $this->data) {
            $this->data = null;
        }
    }

    protected function checkUsernameExists($username)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('u.id')
            ->from('#__users u')
            ->where('u.username = ' . $dbo->quote($username, false));
        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }
}
