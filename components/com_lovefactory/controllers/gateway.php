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

class FrontendControllerGateway extends FrontendController
{
    public function process()
    {
        $model = $this->getModel('gateway', 'FrontendModel');

        if (!$model->process()) {
            JFactory::getApplication()->enqueueMessage($model->getError(), 'warning');

            echo '<h1>' . FactoryText::_('gateway_process_error_title') . '</h1>';
            echo '<p>' . FactoryText::_('gateway_process_error_text') . '</p>';

            return true;
        }

        return true;
    }
}
