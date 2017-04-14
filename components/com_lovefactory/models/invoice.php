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

jimport('joomla.application.component.model');

class FrontendModelInvoice extends FactoryModel
{
    public function getItem()
    {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $id = $app->input->getInt('id');
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true)
            ->select('i.*')
            ->from('#__lovefactory_invoices i')
            ->where('i.id = ' . $dbo->quote($id));

        // Check if invoice is mine or I'm a Super User or on the backend.
        if (!$app->isAdmin() || !$user->authorise('core.admin')) {
            $query->where('i.user_id = ' . $dbo->quote($user->id));
        }

        $result = $dbo->setQuery($query)
            ->loadObject();

        return $result;
    }

    public function getTemplate()
    {
        JLoader::register('JHtmlLoveFactory', JPATH_SITE . '/components/com_lovefactory/lib/html/html.php');

        $settings = new LovefactorySettings();
        $template = $settings->invoice_template;
        $item = $this->getItem();

        if (!$item) {
            return false;
        }

        $search = array(
            '%%seller_information%%',
            '%%buyer_information%%',
            '%%invoice_number%%',
            '%%invoice_date%%',
            '%%membership_title%%',
            '%%membership_price%%',
            '%%vat%%',
            '%%total%%',
        );

        $replace = array(
            $item->seller,
            $item->buyer,
            $item->id,
            JHtml::date($item->issued_at, 'Y-m-d'),
            $item->membership,
            JHtml::_('LoveFactory.currency', $item->price, $item->currency),
            JHtml::_('LoveFactory.currency', $item->vat_value, $item->currency),
            JHtml::_('LoveFactory.currency', $item->total, $item->currency),
        );

        $template = str_replace($search, $replace, $template);

        return $template;
    }
}
