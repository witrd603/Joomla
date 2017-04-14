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

class BackendControllerImport extends BackendController
{
    public function migrate()
    {
        JLoader::register('LoveFactoryImport', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/imports/import.php');

        $adaptor = $this->input->getString('adaptor');
        $adaptor = LoveFactoryImport::getInstance($adaptor);

        $response = $adaptor->import();

        if ($this->isXmlHttpRequest()) {
            echo json_encode($response);

            return true;
        }

        return true;
    }

    protected function isXmlHttpRequest()
    {
        return strtolower($this->input->server->getCmd('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
    }
}
