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

class BackendModelApproval extends JModelLegacy
{
    public function getItem()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $cid = isset($cid[0]) ? $cid[0] : false;

        list($type, $id) = explode('.', $cid);

        if (method_exists($this, 'get' . $type)) {
            return call_user_func_array(array($this, 'get' . $type), array($id));
        }

        return false;
    }

    public function getType()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $cid = isset($cid[0]) ? $cid[0] : false;

        list($type, $id) = explode('.', $cid);

        return $type;
    }

    public function getId()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $cid = isset($cid[0]) ? $cid[0] : false;

        list($type, $id) = explode('.', $cid);

        return $id;
    }

    protected function getProfile($id)
    {
        /** @var TableProfileUpdate $table */
        $table = JTable::getInstance('ProfileUpdate', 'Table');
        $table->load($id);

        $params = new \Joomla\Registry\Registry($table->profile);
        $fields = $params->toObject();

        $profile = JTable::getInstance('Profile', 'Table');
        $profile->load($table->user_id);

        require_once JPATH_SITE . '/components/com_lovefactory/vendor/autoload.php';

        JFactory::getLanguage()->load('com_lovefactory', JPATH_SITE);

        $renderer = \ThePhpFactory\LoveFactory\Factory::buildPageRenderer('viewable');
        $page = LoveFactoryPage::getInstance('profile_view', 'view', array('isAdmin' => true));

        $profile->bind($fields);
        $page->bind($profile);

        return $renderer->render($page);
    }

    protected function getComment($id)
    {
        $comment = JTable::getInstance('Comment', 'Table');
        $comment->load($id);

        return $comment;
    }

    protected function getVideoComment($id)
    {
        $table = JTable::getInstance('ItemComment', 'Table');
        $table->load($id);

        $table->text = $table->message;

        return $table;
    }

    protected function getProfileComment($id)
    {
        $table = JTable::getInstance('ItemComment', 'Table');
        $table->load($id);

        $table->text = $table->message;

        return $table;
    }

    protected function getMessage($id)
    {
        $table = JTable::getInstance('LoveFactoryMessage', 'Table');
        $table->load($id);

        return $table;
    }

    protected function getRequest($id)
    {
        $table = JTable::getInstance('Friend', 'Table');
        $table->load($id);

        return $table;
    }

    protected function getPostGroup($id)
    {
        $table = JTable::getInstance('GroupPost', 'Table');
        $table->load($id);

        return $table;
    }

    protected function getThreadGroup($id)
    {
        $table = JTable::getInstance('GroupThread', 'Table');
        $table->load($id);

        return $table;
    }

    protected function getGroup($id)
    {
        $table = JTable::getInstance('Group', 'Table');
        $table->load($id);

        return $table;
    }

    protected function getPhotoComment($id)
    {
        $table = JTable::getInstance('ItemComment', 'Table');
        $table->load($id);

        $table->text = $table->message;

        return $table;
    }

    protected function getPhoto($id)
    {
        $table = JTable::getInstance('Photo', 'Table');
        $table->load($id);

        return $table;
    }

    protected function getVideo($id)
    {
        $table = JTable::getInstance('LovefactoryVideo', 'Table');
        $table->load($id);

        return $table;
    }

    public function approve($cid, $mode = 'approve', $message)
    {
        $array = array();
        foreach ($cid as $id) {
            list($type, $id) = explode('.', $id);

            $array[$type][] = $id;
        }

        foreach ($array as $type => $items) {
            foreach ($items as $item_id) {

                $item = $this->getObject($type, $item_id);

                if (!$item || !method_exists($item, $mode)) {
                    continue;
                }

                $return = call_user_func_array(array($item, $mode), array());

                if (!$return) {
                    continue;
                }

                $table = JTable::getInstance('Approval', 'Table');
                $table->type = $type;
                $table->item_id = $item_id;
                $table->user_id = isset($item->user_id) ? $item->user_id : $item->sender_id;
                $table->approved = 'approve' == $mode ? 1 : 0;
                $table->message = $message;

                $table->store();
            }
        }

        return true;
    }

    public function getObject($type, $item_id)
    {
        $types = array(
            'profile' => array('ProfileUpdate', 'Table'),
            'photo' => array('Photo', 'Table'),
            'video' => array('LoveFactoryVideo', 'Table'),
            'comment' => array('Comment', 'Table'),
            'photocomment' => array('ItemComment', 'Table'),
            'videocomment' => array('ItemComment', 'Table'),
            'profilecomment' => array('ItemComment', 'Table'),
            'message' => array('LoveFactoryMessage', 'Table'),
            'group' => array('Group', 'Table'),
            'postgroup' => array('GroupPost', 'Table'),
            'threadgroup' => array('GroupThread', 'Table'),
            'request' => array('Friend', 'Table'),
        );

        $table = JTable::getInstance($types[$type][0], $types[$type][1]);

        if (!$table) {
            return false;
        }

        $table->load($item_id);

        return $table;
    }
}
