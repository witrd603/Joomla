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

class FrontendViewPhoto extends FactoryView
{
    protected
        $get = array(
        'item',
        'viewItemComments',
        'nextId',
        'prevId',
        'approvalEnabled'
    );

    public function display($tpl = null)
    {
        parent::display($tpl);

        $model = JModelLegacy::getInstance('Profile', 'FrontendModel');
        $model->getProfile($this->item->user_id);
    }
}
