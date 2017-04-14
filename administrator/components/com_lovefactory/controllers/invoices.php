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

jimport('joomla.application.component.controlleradmin');

class BackendControllerInvoices extends JControllerAdmin
{
    protected $option = 'com_lovefactory';

    public function getModel($name = 'Invoice', $prefix = 'BackendModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);;
    }

    public function export()
    {
        $model = $this->getModel('Invoices');
        $data = $model->getDataForExport();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . 'lovefactory-invoices.csv');
        $fp = fopen('php://output', 'w');

        foreach ($data as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);

        jexit();
    }
}
