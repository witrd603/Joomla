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

class FrontendControllerField extends FrontendController
{
    public function action()
    {
        $input = $this->input;
        $field = $input->getCmd('field');
        $instance = $input->getInt('instance');
        $action = $input->getCmd('action');

        $response = $this->doAction($field, $action, $instance);

        echo json_encode($response);
    }

    private function doAction($field, $action, $instance)
    {
        $class = $field . 'Actions';

        if (!class_exists($class)) {
            $file = JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/fields/' . $field . '/actions.php';

            if (!file_exists($file)) {
                return array(
                    'status' => 0,
                    'error' => 'Field does not have actions!',
                );
            }

            require_once $file;
        }

        if (!class_exists($class)) {
            return array(
                'status' => 0,
                'error' => 'Field does not have actions!',
            );
        }

        $actions = new $class;

        if (!method_exists($actions, $action)) {
            return array(
                'status' => 0,
                'error' => 'Field does not have action!',
            );
        }

        try {
            $response = $actions->$action();
        } catch (Exception $e) {
            return array(
                'status' => 0,
                'error' => $e->getMessage(),
            );
        }

        return array_merge(array('status' => 1), (array)$response);
    }
}
