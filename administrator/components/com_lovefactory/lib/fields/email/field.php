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

class LoveFactoryFieldEmail extends LoveFactoryField
{
    protected $accessPageWhiteList = array('registration');

    public function renderInputEdit()
    {
        $data = htmlentities($this->data, ENT_COMPAT, 'UTF-8');

        $html = array();
        $html[] = '<input type="text" class="lovefactory-field-email" id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" value="' . $data . '" />';

        if ($this->params->get('ajax_check', 1)) {
            FactoryHtml::script('lovefactory');
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $("#' . $this->getHtmlId() . '").LoveFactoryFieldEmailAjaxCheck(); });');

            $html[] = '<div class="error-email-exists" style="display: none;">' . FactoryText::_('signup_email_check_error') . '</div>';
        }

        return implode("\n", $html);
    }

    public function renderInputView()
    {
        return $this->data;
    }

    public function bind($data)
    {
        if (null === $data) {
            return false;
        }

        if (is_object($data)) {
            $data = (array)$data;
        }

        if (is_array($data) && isset($data['email'])) {
            $this->data = $data['email'];
            return true;
        }

        if (!is_array($data) || !isset($data['user_id'])) {
            return false;
        }

        $user = JFactory::getUser($data['user_id']);
        $this->data = $user->email;
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        // Check if email is valid.
        if (!preg_match('/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $this->data)) {
            $this->setError(FactoryText::_('field_email_error_not_valid_email'));
            return false;
        }

        if ($this->checkEmailExists($this->data)) {
            $this->setError(FactoryText::_('field_email_error_email_taken'));
            return false;
        }

        return true;
    }

    public function getId()
    {
        return 'email';
    }

    public function filterData()
    {
        $dbo = JFactory::getDbo();

        $this->data = $dbo->escape(trim($this->data));

        if ('' == $this->data) {
            $this->data = null;
        }
    }

    protected function checkEmailExists($email)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('u.id')
            ->from('#__users u')
            ->where('u.email = ' . $dbo->quote($email, false));
        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }
}
