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

class BackendModelMembershipSoldRestrictions extends JModelLegacy
{
    private $restrictionsMap = array(
        'max_photos' => 'photos',
        'max_videos' => 'videos',
        'max_friends' => 'friends',
        'top_friends' => 'friends_top',
        'same_gender_interaction' => 'same_gender_interaction',
        'max_messages_per_day' => 'messages',
        'max_message_replies_per_day' => 'message_replies',
        'max_interactions_per_day' => 'interactions',
        'shoutbox' => 'shoutbox',
        'groups_create' => 'groups_create',
        'groups_join' => 'groups_join',
        'blogfactory' => 'blog_factory_access',
        'chatfactory' => 'chat_factory_access',
    );

    private $newRestrictions = array(
        'profile_access' => 1,
        'avatar_access' => 1,
        'message_access' => 1,
        'comment_photo_access' => 1,
        'comment_video_access' => 1,
        'comment_profile_access' => 1,
    );

    public function migrate()
    {
        // Copy data from old database to new database.
        if ($this->transformData('MembershipSold')) {
            return false;
        }

        if ($this->transformData('Membership')) {
            return false;
        }

        $this->updateProfiles();

        return true;
    }

    private function transformData($tableName)
    {
        $dbo = $this->getDbo();
        $batch = $this->loadMembershipsBatch($tableName);

        if (!$batch) {
            return false;
        }

        // Copy data to new table.
        $dbo->transactionStart();

        foreach ($batch as $result) {
            $restrictions = new \Joomla\Registry\Registry($this->newRestrictions);

            foreach ($result as $column => $value) {
                if (!isset($this->restrictionsMap[$column])) {
                    continue;
                }

                $restrictions->set($this->restrictionsMap[$column], $value);
            }

            $table = JTable::getInstance($tableName, 'Table');
            $table->save(array(
                'id' => $result['id'],
                'restrictions' => $restrictions->toString(),
            ));
        }

        $dbo->transactionCommit();

        return true;
    }

    private function loadMembershipsBatch($tableName, $limit = 100)
    {
        $dbo = $this->getDbo();
        $table = JTable::getInstance($tableName, 'Table');

        $query = $dbo->getQuery(true)
            ->select('m.*')
            ->from($dbo->qn($table->getTableName(), 'm'))
            ->where('m.restrictions = ' . $dbo->q(''));

        $results = $dbo->setQuery($query, 0, $limit)
            ->loadAssocList();

        return $results;
    }

    private function updateProfiles()
    {
        $dbo = $this->getDbo();
        $table = JTable::getInstance('Profile', 'Table');

        $query = $dbo->getQuery(true)
            ->update($dbo->qn($table->getTableName()))
            ->set('membership_sold_id = ' . $dbo->q(0))
            ->where('membership_sold_id = ' . $dbo->q(1));

        return $dbo->setQuery($query)
            ->execute();
    }
}
