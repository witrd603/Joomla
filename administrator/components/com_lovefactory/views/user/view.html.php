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

class BackendViewUser extends FactoryView
{
    protected $form;

    protected
        $get = array(
        'form',
        'item',
        'state',
        'ips',
        'photos',
        'videos',

        'mode',
        'renderer',
        'page',
        'memberships',
    ),
        $id = 'user_id',
        $title = 'username',
        $buttons = array('apply', 'save', 'close'),
        $behaviors = array('tooltip', 'formvalidation'),
        $css = array('admin/main');

    public function __construct(array $config)
    {
        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';

        JFactory::getLanguage()->load('com_lovefactory', JPATH_SITE);

        parent::__construct($config);
    }

    protected function getMode()
    {
        return JFactory::getApplication()->input->getCmd('mode', 'viewable');
    }

    protected function getRenderer()
    {
        return Factory::buildPageRenderer($this->mode);
    }

    protected function getPage()
    {
        if ('viewable' == $this->mode) {
            $page = 'profile_view';
            $mode = 'view';
        } else {
            $page = 'profile_edit';
            $mode = 'edit';
        }

        $page = LoveFactoryPage::getInstance($page, $mode, array('isAdmin' => true));

        $session = JFactory::getSession();
        $context = 'com_lovefactory.profile.edit.data';

        $profile = $this->item;
        $data = is_null($session->get($context, null)) ? $profile : $session->get($context, null);

        if (!is_null($data)) {
            $page->bind($data);
        }

        $session->set($context, null);

        return $page;
    }
}
