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

class LoveFactoryFieldPassword extends LoveFactoryField
{
    protected $accessPageWhiteList = array('registration');
    protected $passwordValue;

    public function renderInputEdit()
    {
        return '<input type="password" id="' . $this->getHtmlId() . '" name="' . $this->getHtmlName() . '" />';
    }

    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        // Check if password and confirm password match.
        if ($this->params->get('confirmation_for', false) && $this->data != $this->passwordValue) {
            $this->setError(FactoryText::sprintf('field_password_error_passwords_do_not_match'));
            return false;
        }

        return true;
    }

    public function bind($data)
    {
        $confirmation = $this->params->get('confirmation_for', '');

        if ($confirmation) {
            $this->passwordValue = isset($data['field_' . $confirmation]) ? $data['field_' . $confirmation] : null;
        }

        return parent::bind($data);
    }

    public function filterData()
    {
        $this->data = trim($this->data);

        if ('' == $this->data) {
            $this->data = null;
        }
    }
}
