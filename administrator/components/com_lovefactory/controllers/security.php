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

class BackendControllerSecurity extends BackendController
{
    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $this->registerTask('apply', 'save');
    }

    public function save()
    {
        $data = $this->input->post->get('restriction', array(), 'array');
        $model = JModelLegacy::getInstance('Security', 'BackendModel');

        $model->update($data);

        if ('save' === $this->getTask()) {
            $redirect = JRoute::_('index.php?option=com_lovefactory&view=configuration', false);
        } else {
            $redirect = JRoute::_('index.php?option=com_lovefactory&view=security', false);
        }

        $this->setRedirect($redirect);
    }
}
