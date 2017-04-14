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

class TableInvoice extends JTable
{
    var $id = null;
    var $user_id = null;
    var $seller = null;
    var $buyer = null;
    var $membership = null;
    var $price = null;
    var $currency = null;
    var $vat_rate = null;
    var $vat_value = null;
    var $total = null;
    var $issued_at = null;

    function __construct(&$db)
    {
        parent::__construct('#__lovefactory_invoices', 'id', $db);
    }
}
