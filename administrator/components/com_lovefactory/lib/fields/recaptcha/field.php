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

class LoveFactoryFieldReCaptcha extends LoveFactoryField
{
    protected $accessPageWhiteList = array('registration');

    public function renderInputEdit()
    {
        $recaptcha = $this->getRecaptcha();

        if (!$recaptcha) {
            return FactoryText::_('field_recaptcha_key_not_defined');
        }

        return $recaptcha->render($this->params->get('theme', 'red'));
    }

    public function validate()
    {
        $ip = @$_SERVER['REMOTE_ADDR'];
        $challange = @$_POST['recaptcha_challenge_field'];
        $response = @$_POST['recaptcha_response_field'];

        if ($this->field->required && '' == $response) {
            $this->setError(FactoryText::sprintf('field_is_required', $this->getLabel()));
            return false;
        }

        $response = $this->getRecaptcha()->checkAnswer($ip, $challange, $response);

        if (!$response->is_valid) {
            $this->setError(FactoryText::sprintf('field_recaptcha_error_not_valid', $this->getLabel()));
            return false;
        }

        return true;
    }

    public function isRenderable()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();
        if (!$settings->enable_recaptcha) {
            return false;
        }

        return parent::isRenderable();
    }

    protected function getRecaptcha()
    {
        $settings = LoveFactoryApplication::getInstance()->getSettings();

        if (!$settings->recaptcha_public_key) {
            return false;
        }

        $recaptcha = LoveFactoryReCaptcha::getInstance(
            array($settings->recaptcha_public_key, $settings->recaptcha_private_key)
        );

        return $recaptcha;
    }
}
