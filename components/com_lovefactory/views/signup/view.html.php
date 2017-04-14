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

use ThePhpFactory\LoveFactory\Factory;

class FrontendViewSignup extends FactoryView
{
    protected
        $get = array(
        'page',
        'renderer',
        'settings',
    ),
        $css = array('icons'),
        $behaviors = array('factoryTooltip');

    public function display($tpl = null)
    {
        if (JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
            $this->tpl = 'disabled';
            return parent::display();
        }

        $key = LoveFactoryApplication::getInstance()->getSettings('gmaps_api_key', '');
        if ($key) {
            JHtml::script('https://maps.googleapis.com/maps/api/js?key=' . $key);
        }

        parent::display($tpl);

        if ($this->settings->registration_membership) {
            $fields = array('Price' => false, 'Gateway' => false);
            foreach ($this->page->getFields() as $field) {
                if ('Price' === $field->getType()) {
                    $fields['Price'] = true;
                }

                if ('Gateway' === $field->getType()) {
                    $fields['Gateway'] = true;
                }
            }

            if (!$fields['Price'] || !$fields['Gateway']) {
                JFactory::getApplication()->enqueueMessage(FactoryText::_('profile_signup_registration_membership_error'), 'error');
            }
        }
    }

    protected function getRenderer()
    {
        return Factory::buildPageRenderer('editable');
    }
}
