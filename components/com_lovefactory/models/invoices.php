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

class FrontendModelInvoices extends FactoryModelList
{
    protected $defaultOrder = 'desc';

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->sort = array(
            '' => array('text' => FactoryText::_('invoices_filter_sort_date'), 'column' => 'issued_at'),
            'value' => array('text' => FactoryText::_('invoices_filter_sort_value'), 'column' => 'price'),
        );
    }

    public function getIsEnabled()
    {
        return $settings = LoveFactoryApplication::getInstance()->getSettings('enable_invoices');
    }

    protected function getListQuery()
    {
        $userId = JFactory::getUser()->id;
        $query = parent::getListQuery();

        $query->select('i.*')
            ->from('#__lovefactory_invoices i')
            ->where('i.user_id = ' . $query->q($userId));

        return $query;
    }
}
