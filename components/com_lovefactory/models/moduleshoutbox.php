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

class FrontendModelModuleShoutBox extends FrontendModelModule
{
    public function getRestriction()
    {
        $restriction = \ThePhpFactory\LoveFactory\Restrictions\RestrictionFactory::buildRestriction('shoutbox');

        return $restriction;
    }

    public function getEnabled()
    {
        static $enabled = null;

        if (is_null($enabled)) {
            require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_lovefactory' . DS . 'settings.php';
            $settings = new LovefactorySettings();

            $enabled = $settings->enable_shoutbox;
        }

        return $enabled;
    }

    public function getMessages($lastUpdate = null)
    {
        $user = JFactory::getUser();

        if (!$this->getEnabled() || !$this->getRestriction()->isAllowed($user->id)) {
            return array();
        }

        $dbo = $this->getDbo();
        $settings = $this->getSettings();

        $query = $dbo->getQuery(true)
            ->select('m.*')
            ->from('#__lovefactory_shoutbox m');

        $query->select('p.display_name AS username')
            ->leftJoin('#__lovefactory_profiles AS p ON p.user_id = m.sender_id');

        $limit = $settings->shoutbox_messages;

        if (!is_null($lastUpdate)) {
            $query->where('m.id > ' . $dbo->quote(intval($lastUpdate)))
                ->order('m.id ASC');
            $limit = 0;
        } else {
            $query->order('m.id DESC');
        }

        $results = $dbo->setQuery($query, 0, $limit)
            ->loadObjectList();

        $this->setState('lastUpdate', $results ? $results[$lastUpdate ? count($results) - 1 : 0]->id : $lastUpdate);

        if ($this->getParams()) {
            $Itemid = $this->getParams()->get('Itemid', JFactory::getApplication()->input->getInt('Itemid'));
        } else {
            $Itemid = JFactory::getApplication()->input->getInt('Itemid');
        }

        foreach ($results as $result) {
            $html = array();

            $html[] = '<a href="' . FactoryRoute::view('profile&user_id=' . $result->sender_id . '&Itemid=' . $Itemid) . '"><i class="factory-icon icon-user"></i>' . $result->username . '</a>';
            $html[] = $result->message;
            $html[] = '<div class="lovefactory-shoutbox-date">';
            $html[] = JHtml::_('LoveFactory.date', $result->created_at);
            $html[] = '</div>';

            $result->html = implode("\n", $html);
        }

        return $results;
    }

    public function postMessage($message)
    {
        $user = JFactory::getUser();

        if (!$this->getEnabled() || !$this->getRestriction()->hasFullAccess($user->id) || '' == $message) {
            return false;
        }

        $table = $this->getTable('Shoutbox');

        $data['message'] = $message;
        $data['sender_id'] = $user->id;

        if (!$table->save($data)) {
            return false;
        }

        $app = LoveFactoryApplication::getInstance();
        $settings = $app->getSettings();
        if ($settings->shoutbox_log) {
            jimport('joomla.filesystem.file');

            $user = JFactory::getUser();
            $log = $app->getPath('component_administrator') . DS . 'shoutbox_log.txt';

            $contents = file_get_contents($log) . '[' . $table->created_at . '] ' . $user->username . ' (#' . $user->id . '): ' . $table->message . "\n";
            JFile::write($log, $contents);
        }

        return true;
    }

    public function getSettings()
    {
        static $settings = null;

        if (is_null($settings)) {
            $settings = new LovefactorySettings();
        }

        return $settings;
    }

    public function getLastUpdate()
    {
        return $this->getState('lastUpdate');
    }
}
