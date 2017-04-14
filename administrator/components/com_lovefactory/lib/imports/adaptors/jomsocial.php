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

class LoveFactoryImportJomsocial extends LoveFactoryImport
{
    protected $name = 'Jomsocial';
    protected $permissions = array(0 => 0, 20 => 0, 30 => 1, 40 => 2);
    protected $videos = array('"youtube"');

    public function actionFieldsImport()
    {
        // Initialise variables.
        $ids = array();
        $choices = array();

        $typeConversions = array(
            'singleselect' => 'Select',
            'select' => 'Select',
            'list' => 'SelectMultiple',
            'radio' => 'Radio',
            'text' => 'Text',
            'textarea' => 'Textarea',
            'checkbox' => 'Checkbox',
        );
        $fields = $this->getFieldsFromJomsocial();

        // Parse fields.
        foreach ($fields as $field) {
            $descriptions = new JRegistry(array(
                'view' => array('enabled' => 1, 'default' => $field->tips),
                'edit' => array('enabled' => 1, 'default' => $field->tips),
                'search' => array('enabled' => 1, 'default' => $field->tips),
            ));

            $data = array(
                'title' => $field->name,
                'published' => $field->published,
                'required' => $field->required,
                'visibility' => in_array($field->visible, array(0, 1)) ? $field->visible : 0,
                'admin_only_viewable' => $field->visible == 2 ? 1 : 0,
                'descriptions' => $descriptions->toArray(),
            );

            $params = new JRegistry();

            switch ($field->type) {
                case 'singleselect':
                case 'select':
                case 'list':
                case 'radio':
                case 'checkbox':
                    $data['type'] = $typeConversions[$field->type];

                    $params = new JRegistry(array(
                        'choices' => array('default' => explode("\n", $field->options)),
                    ));
                    break;

                case 'country':
                    $data['type'] = 'Select';
                    $xml = simplexml_load_file(JPATH_SITE . '/components/com_community/libraries/fields/countries.xml');
                    $countries = $xml->xpath('//country');
                    $array = array();

                    foreach ($countries as $country) {
                        $array[] = (string)$country->name;
                    }

                    $params = new JRegistry(array('choices' => array('default' => $array)));
                    break;

                case 'gender':
                    $data['type'] = 'Gender';
                    $params = new JRegistry(array('choices' => array('default' => array(
                        'COM_COMMUNITY_MALE',
                        'COM_COMMUNITY_FEMALE',
                    ))));
                    break;

                case 'birthdate':
                    $data['type'] = 'Birthdate';

                    $registry = new JRegistry($field->params);
                    $params = new JRegistry(array(
                        'min_age' => $registry->get('maxrange', 18),
                        'max_age' => $registry->get('minrange', 40),
                    ));
                    break;

                default:
                    $data['type'] = isset($typeConversions[$field->type]) ? $typeConversions[$field->type] : 'Text';
                    break;
            }

            if (isset($data['type'])) {
                $data['params'] = $params->toArray();

                $model = JModelLegacy::getInstance('Field', 'BackendModel');

                if ($model->save($data)) {
                    $ids[intval($field->id)] = $model->getState('field.id');

                    $temp = $params->get('choices', null);

                    if (!is_null($temp)) {
                        $choices[$field->id] = array_flip((array)$temp->default);
                    }
                }
            }
        }

        $this->setParam('fields.ids', $ids);
        $this->setParam('fields.choices', $choices);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionProfilesTruncate()
    {
        // Truncate table.
        $this->truncateTable('#__lovefactory_imports_jomsocial_users');

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionProfilesCount()
    {
        // Count profiles.
        $query = ' SELECT COUNT(1) FROM ' . $this->dbo->quoteName('#__community_users');
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store profiles number.
        $this->setParam('profiles.count', $result);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionProfilesQueue()
    {
        // Initialise variables.
        $limit = $this->getAttribute('limit', 50000);
        $queued = $this->getParam('profiles.queued', 0);
        $total = $this->getParam('profiles.count', 0);
        $columns = $this->prepareColumns(array('user_id'));
        $values = array();

        // Get users from Jomsocial.
        $query = $this->dbo->getQuery(true)
            ->select('u.userid')
            ->from('#__community_users u');
        $results = $this->dbo->setQuery($query, $queued, $limit)
            ->loadColumn();

        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Prepare users values.
        foreach ($results as $id) {
            $values[] = $this->prepareValues(array($id));
        }

        // Import users.
        if ($values) {
            $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_users') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $this->dbo->setQuery($query)->execute();
            $queued += count($values);

            $this->setParam('profiles.queued', $queued);
        }

        // Set action percent.
        $this->setActionPercent($queued * 100 / $total);

        // Mark action as finished.
        if (!$values || count($values) < $limit) {
            $this->finishAction();
        }

        return true;
    }

    public function actionProfilesImport()
    {
        // Initialise variables.
        $ids = $this->getParam('fields.ids', array());
        $choices = $this->getParam('fields.choices', array());
        $imported = $this->getParam('profiles.imported', 0);
        $total = $this->getParam('profiles.queued', 0);
        $limit = $this->getAttribute('limit', 1000);
        $counter = 0;
        $array = array();

        // Get users pending import.
        $query = $this->dbo->getQuery(true)
            ->select('u.user_id')
            ->from('#__lovefactory_imports_jomsocial_users u')
            ->where('u.imported = ' . $this->dbo->quote(0));
        $results = $this->dbo->setQuery($query, 0, $limit)
            ->loadColumn();

        if (!$results) {
            $this->finishAction();
            return true;
        }

        foreach ($results as $result) {
            // Check if system resources are available.
            if (!$this->areResourcesAvailable()) {
                break;
            }

            $loveProfile = JTable::getInstance('Profile', 'Table');

            $data = array(
                'user_id' => $result,
                'membership_sold_id' => 0,
                'validated' => 1
            );

            // Get all field values for user.
            $query = $this->dbo->getQuery(true)
                ->select('v.*, f.type')
                ->from('#__community_fields_values v')
                ->leftJoin('#__community_fields f ON f.id = v.field_id')
                ->where('v.user_id = ' . $this->dbo->quote($result));
            $fields = $this->dbo->setQuery($query)
                ->loadObjectList();

            foreach ($fields as $field) {
                $id = $field->field_id;

                switch ($field->type) {
                    case 'text':
                    case 'textarea':
                        $data['field_' . $ids->$id] = $field->value;
                        break;

                    case 'select':
                    case 'singleselect':
                    case 'radio':
                    case 'country':
                        $data['field_' . $ids->$id] = isset($choices->$id->{$field->value})
                            ? $choices->$id->{$field->value}
                            : $field->value;
                        break;

                    case 'gender':
                        $data['sex'] = $choices->$id->{$field->value};
                        break;

                    case 'birthdate':
                        if ($field->value) {
                            $date = JFactory::getDate($field->value);
                            $data['field_' . $ids->$id] = $date->format('Ymd');
                        }
                        break;

                    case 'list':
                    case 'checkbox':
                        $array = array();
                        $values = explode(',', $field->value);

                        foreach ($values as $value) {
                            if ('' == $value) {
                                continue;
                            }

                            $array[] = $choices->$id->$value;
                        }

                        $data['field_' . $ids->$id] = '/' . implode('/', $array) . '/';
                        break;

                    default:
                        $data['field_' . $ids->$id] = $field->value;
                        break;
                }
            }

            $loveProfile->save($data);

            // Import avatar.
            $this->importProfileAvatar($loveProfile);

            $array[] = $result;
            $counter++;
        }

        // Update imported profiles.
        $imported += $counter;

        if ($array) {
            $query = ' UPDATE ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_users')
                . ' SET ' . $this->dbo->quoteName('imported') . ' = ' . $this->dbo->quote(1)
                . ' WHERE ' . $this->dbo->quoteName('user_id') . ' IN (' . implode(',', $array) . ')';
            $this->dbo->setQuery($query)->execute();
        }

        // Set profiles imported.
        $this->setParam('profiles.imported', $imported);

        // Set action percent.
        $this->setActionPercent($imported * 100 / $total);

        // Mark action as finished.
        if ($imported == $total) {
            $this->finishAction();
        }

        return true;
    }

    public function actionBlockListCount()
    {
        // Count blocklist.
        $query = ' SELECT COUNT(1) FROM ' . $this->dbo->quoteName('#__community_blocklist');
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store profiles number.
        $this->setParam('blocklist.count', $result);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionBlockListImport()
    {
        // Initialise variables.
        $dbo = JFactory::getDbo();
        $limit = $this->getAttribute('limit', 50000);
        $imported = $this->getParam('blocklist.imported', 0);
        $total = $this->getParam('blocklist.count', 0);
        $values = array();
        $columns = $this->prepareColumns(array('sender_id', 'receiver_id'));

        // Get block list users.
        $query = $dbo->getQuery(true)
            ->select('b.userid, b.blocked_userid')
            ->from('#__community_blocklist b');
        $results = $dbo->setQuery($query, $imported, $limit)
            ->loadObjectList();

        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Prepare block list values.
        foreach ($results as $result) {
            $values[] = $this->prepareValues(array(
                $result->userid,
                $result->blocked_userid,
            ));
        }

        // Import block list.
        if ($values) {
            $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_blacklist') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $dbo->setQuery($query)->execute();
            $imported += count($values);

            $this->setParam('blocklist.imported', $imported);
        }

        // Set action percent.
        $this->setActionPercent($imported * 100 / $total);

        // Mark action as finished.
        if (!$values || count($values) < $limit) {
            $this->finishAction();
        }

        return true;
    }

    public function actionMessagesCount()
    {
        // Count blocklist.
        $query = ' SELECT COUNT(1) FROM ' . $this->dbo->quoteName('#__community_msg');
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store profiles number.
        $this->setParam('messages.count', $result);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionMessagesImport()
    {
        // Initialise variables.
        $dbo = JFactory::getDbo();
        $limit = $this->getAttribute('limit', 50000);
        $imported = $this->getParam('messages.imported', 0);
        $total = $this->getParam('messages.count', 0);
        $values = array();
        $columns = $this->prepareColumns(array('sender_id', 'receiver_id', 'date', 'title', 'text'));

        // Get users messages.
        $query = $dbo->getQuery(true)
            ->select('m.from, m.posted_on, m.subject, m.body, r.to')
            ->from('#__community_msg m')
            ->leftJoin('#__community_msg_recepient r ON r.msg_id = m.id');
        $results = $dbo->setQuery($query, $imported, $limit)
            ->loadObjectList();

        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Prepare message values.
        foreach ($results as $result) {
            $values[] = $this->prepareValues(array(
                $result->from,
                $result->to,
                $result->posted_on,
                $result->subject,
                $result->body,
            ));
        }

        // Import messages.
        if ($values) {
            $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_messages') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $dbo->setQuery($query)->execute();
            $imported += count($values);

            $this->setParam('messages.imported', $imported);
        }

        // Set action percent.
        $this->setActionPercent($imported * 100 / $total);

        // Mark action as finished.
        if (!$values || count($values) < $limit) {
            $this->finishAction();
        }

        return true;
    }

    public function actionPhotosTruncate()
    {
        // Truncate table.
        $this->truncateTable('#__lovefactory_imports_jomsocial_photos');

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionPhotosCount()
    {
        // Count photos.
        $query = ' SELECT COUNT(1) FROM ' . $this->dbo->quoteName('#__community_photos') . ' WHERE storage = ' . $this->dbo->quote('file');
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store photos counter.
        $this->setParam('photos.count', $result);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionPhotosQueue()
    {
        // Initialise variables.
        $limit = $this->getAttribute('limit', 50000);
        $queued = $this->getParam('photos.queued', 0);
        $total = $this->getParam('photos.count', 0);
        $columns = $this->prepareColumns(array('photo_id', 'user_id'));
        $values = array();

        // Get photos from Jomsocial.
        $query = $this->dbo->getQuery(true)
            ->select('p.id, p.creator')
            ->from('#__community_photos p');
        $results = $this->dbo->setQuery($query, $queued, $limit)
            ->loadObjectList();

        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Prepare photos values.
        foreach ($results as $result) {
            $values[] = $this->prepareValues(array($result->id, $result->creator));
        }

        // Import photos.
        if ($values) {
            $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_photos') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $this->dbo->setQuery($query)->execute();
            $queued += count($values);

            $this->setParam('photos.queued', $queued);
        }

        // Set action percent.
        $this->setActionPercent($queued * 100 / $total);

        // Mark action as finished.
        if (!$values || count($values) < $limit) {
            $this->finishAction();
        }

        return true;
    }

    public function actionPhotosImport()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        // Initialise variables.
        $dbo = $this->dbo;
        $imported = $this->getParam('photos.imported', 0);
        $queued = $this->getParam('photos.queued', 0);
        $limit = $this->getAttribute('limit', 1000);
        $columns = $this->prepareColumns(array('user_id', 'filename', 'date_added', 'status'));
        $counter = 0;

        // Get photos pending import.
        $query = $dbo->getQuery(true)
            ->select('p.id, p.creator, p.permissions, p.image, p.created, p.thumbnail')
            ->from('#__lovefactory_imports_jomsocial_photos q')
            ->innerJoin('#__community_photos p ON p.id = q.photo_id')
            ->where('q.imported_id = ' . $dbo->quote(0));
        $results = $dbo->setQuery($query, 0, $limit)
            ->loadObjectList();

        // Check if any photos were found.
        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Parse photos.
        foreach ($results as $result) {
            // Check if system resources are available.
            if (!$this->areResourcesAvailable()) {
                break;
            }

            $import = $this->importPhoto($result, $columns);
            if ($import) {
                $this->copyPhoto($result);
            }

            $query = ' UPDATE ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_photos')
                . ' SET ' . $this->dbo->quoteName('imported_id') . ' = ' . $this->dbo->quote($import)
                . ' WHERE ' . $this->dbo->quoteName('photo_id') . ' = ' . $this->dbo->quote($result->id);
            $dbo->setQuery($query)->execute();

            $counter++;
        }

        // Update imported profiles.
        $imported += $counter;

        // Check if all photos were imported.
        if ($imported === $queued) {
            $this->finishAction();
            return true;
        }

        // Set photos imported.
        $this->setParam('photos.imported', $imported);

        // Set action percent.
        $this->setActionPercent($imported * 100 / $queued);

        return true;
    }

    public function actionPhotosComments()
    {
        // Initialise variables.
        $dbo = $this->dbo;
        $comments = $this->getParam('photos.comments', 0);
        $queued = $this->getParam('photos.queued', 0);
        $limit = $this->getAttribute('limit', 1000);
        $columns = $this->prepareColumns(array('item_type', 'item_id', 'item_user_id', 'user_id', 'message', 'created_at'));
        $ids = array();
        $counter = 0;

        // Get photos comments pending import.
        $query = $dbo->getQuery(true)
            ->select('q.photo_id, q.imported_id, q.user_id')
            ->from('#__lovefactory_imports_jomsocial_photos q')
            ->where('q.comments = ' . $dbo->quote(0))
            ->where('q.imported_id <> ' . $dbo->quote(0));
        $results = $dbo->setQuery($query, 0, $limit)
            ->loadObjectList();

        // Check if any photo comments were found.
        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Parse photos.
        foreach ($results as $result) {
            // Check if system resources are available.
            if (!$this->areResourcesAvailable()) {
                break;
            }

            // Get comments for current photo.
            $query = $dbo->getQuery(true)
                ->select('w.post_by, w.comment, w.date')
                ->from('#__community_wall w')
                ->where('w.type = ' . $dbo->quote('photos'))
                ->where('w.contentid = ' . $dbo->quote($result->photo_id));
            $records = $dbo->setQuery($query)
                ->loadObjectList();

            if ($records) {
                $values = array();
                foreach ($records as $comment) {
                    $values[] = $this->prepareValues(array(
                        'photo',
                        $result->imported_id,
                        $result->user_id,
                        $comment->post_by,
                        $comment->comment,
                        $comment->date,
                    ));
                }

                $query = ' INSERT INTO ' . $dbo->quoteName('#__lovefactory_item_comments') . ' ' . $columns . ' VALUES ' . implode(', ', $values);
                $dbo->setQuery($query)->execute();
            }

            $ids[] = $result->photo_id;

            $counter++;
        }

        // Mark photo comments as imported.
        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_imports_jomsocial_photos')
            ->set($dbo->quoteName('comments') . ' = ' . $dbo->quote(1))
            ->where($dbo->quoteName('photo_id') . ' IN (' . implode(',', $ids) . ')');
        $dbo->setQuery($query)->execute();

        // Update imported photo comments.
        $comments += $counter;

        // Check if all photo comments were imported.
        if ($comments === $queued) {
            $this->finishAction();
            return true;
        }

        // Set photo comments imported.
        $this->setParam('photos.comments', $comments);

        // Set action percent.
        $this->setActionPercent($comments * 100 / $queued);

        return true;
    }

    public function actionVideosTruncate()
    {
        // Truncate table.
        $this->truncateTable('#__lovefactory_imports_jomsocial_videos');

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionVideosCount()
    {
        // Count photos.
        $query = ' SELECT COUNT(1) FROM ' . $this->dbo->quoteName('#__community_videos') . ' WHERE ' . $this->dbo->quoteName('type') . ' IN (' . implode(',', $this->videos) . ')';
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store photos counter.
        $this->setParam('videos.count', $result);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionVideosQueue()
    {
        // Initialise variables.
        $limit = $this->getAttribute('limit', 50000);
        $queued = $this->getParam('videos.queued', 0);
        $total = $this->getParam('videos.count', 0);
        $columns = $this->prepareColumns(array('video_id', 'user_id'));
        $values = array();

        // Get videos from Jomsocial.
        $query = $this->dbo->getQuery(true)
            ->select('v.id, v.creator')
            ->from('#__community_videos v')
            ->where('v.type IN (' . implode(',', $this->videos) . ')');
        $results = $this->dbo->setQuery($query, $queued, $limit)
            ->loadObjectList();

        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Prepare videos values.
        foreach ($results as $result) {
            $values[] = $this->prepareValues(array($result->id, $result->creator));
        }

        // Import photos.
        if ($values) {
            $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_videos') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $this->dbo->setQuery($query)->execute();
            $queued += count($values);

            $this->setParam('videos.queued', $queued);
        }

        // Set action percent.
        $this->setActionPercent($queued * 100 / $total);

        // Mark action as finished.
        if (!$values || count($values) < $limit) {
            $this->finishAction();
        }

        return true;
    }

    public function actionVideosImport()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        // Initialise variables.
        $dbo = $this->dbo;
        $imported = $this->getParam('videos.imported', 0);
        $queued = $this->getParam('videos.queued', 0);
        $limit = $this->getAttribute('limit', 1000);
        $columns = $this->prepareColumns(array('user_id', 'code', 'title', 'description', 'date_added', 'status', 'thumbnail'));
        $counter = 0;

        // Get photos pending import.
        $query = $dbo->getQuery(true)
            ->select('v.id, v.creator, v.title, v.created, v.description, v.video_id, v.type, v.permissions, v.thumb')
            ->from('#__lovefactory_imports_jomsocial_videos q')
            ->innerJoin('#__community_videos v ON v.id = q.video_id')
            ->where('q.imported_id = ' . $dbo->quote(0));
        $results = $dbo->setQuery($query, 0, $limit)
            ->loadObjectList();

        // Check if any photos were found.
        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Parse photos.
        foreach ($results as $result) {
            // Check if system resources are available.
            if (!$this->areResourcesAvailable()) {
                break;
            }

            $import = $this->importVideo($result, $columns);
            if ($import) {
                $this->copyVideoThumbnail($result);
            }

            $query = ' UPDATE ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_videos')
                . ' SET ' . $this->dbo->quoteName('imported_id') . ' = ' . $this->dbo->quote($import)
                . ' WHERE ' . $this->dbo->quoteName('video_id') . ' = ' . $this->dbo->quote($result->id);
            $dbo->setQuery($query)->execute();

            $counter++;
        }

        // Update imported profiles.
        $imported += $counter;

        // Check if all photos were imported.
        if ($imported === $queued) {
            $this->finishAction();
            return true;
        }

        // Set photos imported.
        $this->setParam('videos.imported', $imported);

        // Set action percent.
        $this->setActionPercent($imported * 100 / $queued);

        return true;
    }

    public function actionVideosComments()
    {
        // Initialise variables.
        $dbo = $this->dbo;
        $comments = $this->getParam('videos.comments', 0);
        $queued = $this->getParam('videos.queued', 0);
        $limit = $this->getAttribute('limit', 1000);
        $columns = $this->prepareColumns(array('item_type', 'item_id', 'item_user_id', 'user_id', 'message', 'created_at'));
        $ids = array();
        $counter = 0;

        // Get video comments pending import.
        $query = $dbo->getQuery(true)
            ->select('q.video_id, q.imported_id, q.user_id')
            ->from('#__lovefactory_imports_jomsocial_videos q')
            ->where('q.comments = ' . $dbo->quote(0))
            ->where('q.imported_id <> ' . $dbo->quote(0));
        $results = $dbo->setQuery($query, 0, $limit)
            ->loadObjectList();

        // Check if any video comments were found.
        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Parse videos.
        foreach ($results as $result) {
            // Check if system resources are available.
            if (!$this->areResourcesAvailable()) {
                break;
            }

            // Get comments for current video.
            $query = $dbo->getQuery(true)
                ->select('w.post_by, w.comment, w.date')
                ->from('#__community_wall w')
                ->where('w.type = ' . $dbo->quote('videos'))
                ->where('w.contentid = ' . $dbo->quote($result->video_id));
            $records = $dbo->setQuery($query)
                ->loadObjectList();

            if ($records) {
                $values = array();
                foreach ($records as $comment) {
                    $values[] = $this->prepareValues(array(
                        'video',
                        $result->imported_id,
                        $result->user_id,
                        $comment->post_by,
                        $comment->comment,
                        $comment->date,
                    ));
                }

                $query = ' INSERT INTO ' . $dbo->quoteName('#__lovefactory_item_comments') . ' ' . $columns . ' VALUES ' . implode(', ', $values);
                $dbo->setQuery($query)->execute();
            }

            $ids[] = $result->video_id;

            $counter++;
        }

        // Mark video comments as imported.
        $query = $dbo->getQuery(true)
            ->update('#__lovefactory_imports_jomsocial_videos')
            ->set($dbo->quoteName('comments') . ' = ' . $dbo->quote(1))
            ->where($dbo->quoteName('video_id') . ' IN (' . implode(',', $ids) . ')');
        $dbo->setQuery($query)->execute();

        // Update imported photo comments.
        $comments += $counter;

        // Check if all photo comments were imported.
        if ($comments === $queued) {
            $this->finishAction();
            return true;
        }

        // Set photo comments imported.
        $this->setParam('videos.comments', $comments);

        // Set action percent.
        $this->setActionPercent($comments * 100 / $queued);

        return true;
    }

    public function actionGroupsTruncate()
    {
        // Truncate table.
        $this->truncateTable('#__lovefactory_imports_jomsocial_groups');

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionGroupsCount()
    {
        // Count groups.
        $query = ' SELECT COUNT(1) FROM ' . $this->dbo->quoteName('#__community_groups');
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store groups counter.
        $this->setParam('groups.count', $result);

        // Count members.
        $query = ' SELECT COUNT(1) FROM ' . $this->dbo->quoteName('#__community_groups_members');
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store members counter.
        $this->setParam('groups.members_count', $result);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionGroupsQueue()
    {
        // Initialise variables.
        $limit = $this->getAttribute('limit', 50000);
        $queued = $this->getParam('groups.queued', 0);
        $total = $this->getParam('groups.count', 0);
        $columns = $this->prepareColumns(array('group_id'));
        $values = array();

        // Get videos from Jomsocial.
        $query = $this->dbo->getQuery(true)
            ->select('g.id')
            ->from('#__community_groups g');
        $results = $this->dbo->setQuery($query, $queued, $limit)
            ->loadColumn();

        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Prepare groups values.
        foreach ($results as $id) {
            $values[] = $this->prepareValues(array($id));
        }

        // Import groups.
        if ($values) {
            $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_groups') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $this->dbo->setQuery($query)->execute();
            $queued += count($values);

            $this->setParam('groups.queued', $queued);
        }

        // Set action percent.
        $this->setActionPercent($queued * 100 / $total);

        // Mark action as finished.
        if (!$values || count($values) < $limit) {
            $this->finishAction();
        }

        return true;
    }

    public function actionGroupsImport()
    {
        // Initialise variables.
        $dbo = $this->dbo;
        $imported = $this->getParam('groups.imported', 0);
        $queued = $this->getParam('groups.queued', 0);
        $limit = $this->getAttribute('limit', 1000);
        $columns = $this->prepareColumns(array('user_id', 'title', 'description', 'created_at', 'private'));
        $counter = 0;

        // Get groups pending import.
        $query = $dbo->getQuery(true)
            ->select('g.id, g.ownerid, g.name, g.description, g.created, g.approvals')
            ->from('#__lovefactory_imports_jomsocial_groups q')
            ->innerJoin('#__community_groups g ON g.id = q.group_id')
            ->where('q.imported_id = ' . $dbo->quote(0));
        $results = $dbo->setQuery($query, 0, $limit)
            ->loadObjectList();

        // Check if any groups were found.
        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Parse groups.
        foreach ($results as $result) {
            // Check if system resources are available.
            if (!$this->areResourcesAvailable()) {
                break;
            }

            $import = $this->importGroup($result, $columns);

            $query = ' UPDATE ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_groups')
                . ' SET ' . $this->dbo->quoteName('imported_id') . ' = ' . $this->dbo->quote($import)
                . ' WHERE ' . $this->dbo->quoteName('group_id') . ' = ' . $this->dbo->quote($result->id);
            $dbo->setQuery($query)->execute();

            $counter++;
        }

        // Update imported profiles.
        $imported += $counter;

        // Check if all photos were imported.
        if ($imported === $queued) {
            $this->finishAction();
            return true;
        }

        // Set photos imported.
        $this->setParam('groups.imported', $imported);

        // Set action percent.
        $this->setActionPercent($imported * 100 / $queued);

        return true;
    }

    public function actionGroupsMembers()
    {
        // Initialise variables.
        $dbo = $this->dbo;
        $imported = $this->getParam('groups.members_imported', 0);
        $queued = $this->getParam('groups.members_count', 0);
        $limit = $this->getAttribute('limit', 1000);
        $columns = $this->prepareColumns(array('user_id', 'group_id'));
        $counter = 0;
        $values = array();

        // Get members pending import.
        $query = $dbo->getQuery(true)
            ->select('m.memberid, g.imported_id')
            ->from('#__community_groups_members m')
            ->innerJoin('#__lovefactory_imports_jomsocial_groups g ON g.group_id = m.groupid');
        $results = $dbo->setQuery($query, $imported, $limit)
            ->loadObjectList();

        // Check if any members were found.
        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Parse members.
        foreach ($results as $result) {
            $values[] = $this->prepareValues(array(
                $result->memberid,
                $result->imported_id,
            ));

            $counter++;
        }

        // Import members.
        if ($values) {
            $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_group_members') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $dbo->setQuery($query)->execute();
            $imported += count($values);

            $this->setParam('groups.members_imported', $imported);
        }

        // Check if all members were imported.
        if ($imported === $queued) {
            $this->finishAction();
            return true;
        }

        // Set action percent.
        $this->setActionPercent($imported * 100 / $queued);

        return true;
    }

    public function actionDiscussionsTruncate()
    {
        // Truncate table.
        $this->truncateTable('#__lovefactory_imports_jomsocial_discuss');

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionDiscussionsCount()
    {
        // Count discussions.
        $query = ' SELECT COUNT(1) FROM ' . $this->dbo->quoteName('#__community_groups_discuss');
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store discussions counter.
        $this->setParam('discussions.count', $result);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionDiscussionsQueue()
    {
        // Initialise variables.
        $limit = $this->getAttribute('limit', 50000);
        $queued = $this->getParam('discussions.queued', 0);
        $total = $this->getParam('discussions.count', 0);
        $columns = $this->prepareColumns(array('discuss_id', 'imported_group_id'));
        $values = array();

        // Get discussions from Jomsocial.
        $query = $this->dbo->getQuery(true)
            ->select('d.id, g.imported_id')
            ->from('#__community_groups_discuss d')
            ->innerJoin('#__lovefactory_imports_jomsocial_groups g ON g.group_id = d.groupid');
        $results = $this->dbo->setQuery($query, $queued, $limit)
            ->loadObjectList();

        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Prepare discussions values.
        foreach ($results as $result) {
            $values[] = $this->prepareValues(array($result->id, $result->imported_id));
        }

        // Queue discussions.
        if ($values) {
            $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_discuss') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $this->dbo->setQuery($query)->execute();
            $queued += count($values);

            $this->setParam('discussions.queued', $queued);
        }

        // Set action percent.
        $this->setActionPercent($queued * 100 / $total);

        // Mark action as finished.
        if (!$values || count($values) < $limit) {
            $this->finishAction();
        }

        return true;
    }

    public function actionDiscussionsImport()
    {
        // Initialise variables.
        $dbo = $this->dbo;
        $imported = $this->getParam('discussions.imported', 0);
        $queued = $this->getParam('discussions.queued', 0);
        $limit = $this->getAttribute('limit', 1000);
        $columns = $this->prepareColumns(array('group_id', 'user_id', 'title', 'text', 'created_at'));
        $counter = 0;

        // Get discussions pending import.
        $query = $dbo->getQuery(true)
            ->select('d.id, q.imported_group_id, d.creator, d.title, d.message, d.created')
            ->from('#__lovefactory_imports_jomsocial_discuss q')
            ->innerJoin('#__community_groups_discuss d ON d.id = q.discuss_id')
            ->where('q.imported_id = ' . $dbo->quote(0));
        $results = $dbo->setQuery($query, 0, $limit)
            ->loadObjectList();

        // Check if any groups were found.
        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Parse groups.
        foreach ($results as $result) {
            // Check if system resources are available.
            if (!$this->areResourcesAvailable()) {
                break;
            }

            $import = $this->importDiscussion($result, $columns);

            $query = ' UPDATE ' . $this->dbo->quoteName('#__lovefactory_imports_jomsocial_discuss')
                . ' SET ' . $this->dbo->quoteName('imported_id') . ' = ' . $this->dbo->quote($import)
                . ' WHERE ' . $this->dbo->quoteName('discuss_id') . ' = ' . $this->dbo->quote($result->id);
            $dbo->setQuery($query)->execute();

            $counter++;
        }

        // Update imported profiles.
        $imported += $counter;

        // Check if all photos were imported.
        if ($imported === $queued) {
            $this->finishAction();
            return true;
        }

        // Set photos imported.
        $this->setParam('discussions.imported', $imported);

        // Set action percent.
        $this->setActionPercent($imported * 100 / $queued);

        return true;
    }

    public function actionRepliesCount()
    {
        // Count replies.
        $query = $this->dbo->getQuery(true)
            ->select('COUNT(1)')
            ->from($this->dbo->quoteName('#__community_wall'))
            ->where($this->dbo->quoteName('type') . ' = ' . $this->dbo->quote('discussions'));
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store replies counter.
        $this->setParam('replies.count', $result);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionRepliesImport()
    {
        // Initialise variables.
        $dbo = $this->dbo;
        $imported = $this->getParam('replies.imported', 0);
        $total = $this->getParam('replies.count', 0);
        $limit = $this->getAttribute('limit', 1000);
        $columns = $this->prepareColumns(array('group_id', 'thread_id', 'user_id', 'text', 'created_at'));
        $values = array();
        $counter = 0;

        // Get replies pending import.
        $query = $dbo->getQuery(true)
            ->select('d.imported_group_id, d.imported_id, w.post_by, w.comment, w.date')
            ->from($this->dbo->quoteName('#__community_wall') . ' w')
            ->where($this->dbo->quoteName('type') . ' = ' . $this->dbo->quote('discussions'))
            ->innerJoin($this->dbo->quoteName('#__lovefactory_imports_jomsocial_discuss') . ' d ON d.discuss_id = w.contentid');
        $results = $dbo->setQuery($query, $imported, $limit)
            ->loadObjectList();

        // Check if any replies were found.
        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Parse replies.
        foreach ($results as $result) {
            $values[] = $this->prepareValues(array(
                $result->imported_group_id,
                $result->imported_id,
                $result->post_by,
                $result->comment,
                $result->date,
            ));

            $counter++;
        }

        if ($values) {
            $query = ' INSERT INTO ' . $dbo->quoteName('#__lovefactory_group_posts') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $dbo->setQuery($query)->execute();

            // Update imported replies.
            $imported += $counter;

            // Set photos imported.
            $this->setParam('replies.imported', $imported);
        }

        // Check if all photos were imported.
        if ($imported === $total) {
            $this->finishAction();
            return true;
        }

        // Set action percent.
        $this->setActionPercent($imported * 100 / $total);

        return true;
    }

    public function actionFriendsCount()
    {
        // Count friends.
        $query = $this->dbo->getQuery(true)
            ->select('COUNT(1)')
            ->from($this->dbo->quoteName('#__community_connection'));
        $result = $this->dbo->setQuery($query)->loadResult();

        // Store replies counter.
        $this->setParam('friends.count', $result / 2);

        // Mark action as finished.
        $this->finishAction();

        return true;
    }

    public function actionFriendsImport()
    {
        // Initialise variables.
        $dbo = $this->dbo;
        $imported = $this->getParam('friends.imported', 0);
        $total = $this->getParam('friends.count', 0);
        $limit = $this->getAttribute('limit', 10000);
        $columns = $this->prepareColumns(array('type', 'sender_id', 'receiver_id', 'date', 'pending'));
        $values = array();
        $counter = 0;

        // Get friends from Jomsocial.
        $query = $dbo->getQuery(true)
            ->select('DISTINCT CONCAT_WS(' . $this->dbo->quote('.') . ', IF(c.connect_from < c.connect_to, c.connect_from, c.connect_to), IF(c.connect_from > c.connect_to, c.connect_from, c.connect_to)) AS hash')
            ->select('c.connect_from, c.connect_to, c.status, c.created')
            ->from($this->dbo->quoteName('#__community_connection') . ' c')
            ->group('hash')
            ->order('hash ASC');
        $results = $dbo->setQuery($query, $imported, $limit)
            ->loadObjectList();

        // Check if any friends were found.
        if (!$results) {
            $this->finishAction();
            return true;
        }

        // Parse friends.
        foreach ($results as $result) {
            $values[] = $this->prepareValues(array(
                1,
                $result->connect_from,
                $result->connect_to,
                $result->created,
                intval(!$result->status),
            ));

            $counter++;
        }

        if ($values) {
            $query = ' INSERT INTO ' . $dbo->quoteName('#__lovefactory_friends') . ' ' . $columns . ' VALUES ' . implode(',', $values);
            $dbo->setQuery($query)->execute();

            // Update imported replies.
            $imported += $counter;

            // Set photos imported.
            $this->setParam('friends.imported', $imported);
        }

        // Check if all friends were imported.
        if ($imported === $total) {
            $this->finishAction();
            return true;
        }

        // Set action percent.
        $this->setActionPercent($imported * 100 / $total);

        return true;
    }

    protected function getFieldsFromJomsocial()
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('f.*')
            ->from('#__community_fields f')
            ->where('f.type <> ' . $dbo->q('group'));

        $results = $dbo->setQuery($query)
            ->loadObjectList();

        return $results;
    }

    protected function importPhoto($photo, $columns)
    {
        $values = $this->prepareValues(array(
            $photo->creator,
            basename($photo->image),
            $photo->created,
            @$this->permissions[$photo->permissions],
        ));

        $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_photos') . ' ' . $columns . ' VALUES ' . $values;
        if ($this->dbo->setQuery($query)->execute()) {
            return $this->dbo->insertid();
        }

        return 0;
    }

    protected function importVideo($video, $columns)
    {
        $embed = array(
            'youtube' => '<iframe width="420" height="315" src="http://www.youtube.com/embed/{video_id}" frameborder="0" allowfullscreen></iframe>',
        );

        $values = $this->prepareValues(array(
            $video->creator,
            str_replace('{video_id}', $video->video_id, $embed[$video->type]),
            $video->title,
            $video->description,
            $video->created,
            $this->permissions[$video->permissions],
            'video_' . basename($video->thumb),
        ));

        $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_videos') . ' ' . $columns . ' VALUES ' . $values;
        if ($this->dbo->setQuery($query)->execute()) {
            return $this->dbo->insertid();
        }

        return 0;
    }

    protected function importGroup($group, $columns)
    {
        $values = $this->prepareValues(array(
            $group->ownerid,
            $group->name,
            $group->description,
            $group->created,
            $group->approvals,
        ));

        $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_groups') . ' ' . $columns . ' VALUES ' . $values;
        if ($this->dbo->setQuery($query)->execute()) {
            return $this->dbo->insertid();
        }

        return 0;
    }

    protected function importDiscussion($discussion, $columns)
    {
        $values = $this->prepareValues(array(
            $discussion->imported_group_id,
            $discussion->creator,
            $discussion->title,
            $discussion->message,
            $discussion->created,
        ));

        $query = ' INSERT INTO ' . $this->dbo->quoteName('#__lovefactory_group_threads') . ' ' . $columns . ' VALUES ' . $values;
        if ($this->dbo->setQuery($query)->execute()) {
            return $this->dbo->insertid();
        }

        return 0;
    }

    protected function copyPhoto($photo)
    {
        // Set base folder.
        $folder = JPATH_SITE . '/media/com_lovefactory/storage/photos/' . $photo->creator;

        // Check if Love Factory user folder exists.
        if (!JFolder::exists($folder)) {
            // Try to create user folder.
            if (!JFolder::create($folder)) {
                return false;
            }
        }

        // Process photo.
        $src = JPATH_SITE . '/' . $photo->image;
        $dest = $folder . '/' . basename($photo->image);

        // Copy file.
        if (!JFile::exists($src) || !JFile::copy($src, $dest)) {
            return false;
        }

        // Process thumbnail.
        $src = JPATH_SITE . '/' . $photo->thumbnail;
        $dest = $folder . '/' . basename($photo->thumbnail);

        // Copy thumbnail.
        if (!JFile::exists($src) || !JFile::copy($src, $dest)) {
            return false;
        }

        return true;
    }

    protected function copyVideoThumbnail($video)
    {
        // Set base folder.
        $folder = JPATH_SITE . '/media/com_lovefactory/storage/photos/' . $video->creator;

        // Check if Love Factory user folder exists.
        if (!JFolder::exists($folder)) {
            // Try to create user folder.
            if (!JFolder::create($folder)) {
                return false;
            }
        }

        // Process thumbnail.
        $src = JPATH_SITE . '/' . $video->thumb;
        $dest = $folder . '/video_' . basename($video->thumb);

        // Copy thumbnail.
        if (!JFile::exists($src) || !JFile::copy($src, $dest)) {
            return false;
        }

        return true;
    }

    protected function importProfileAvatar(TableProfile $profile)
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        // Get avatar from Jomsocial profile.
        $query = $this->dbo->getQuery(true)
            ->select('u.avatar')
            ->from($this->dbo->qn('#__community_users', 'u'))
            ->where('u.userid = ' . $this->dbo->q($profile->user_id));
        $avatar = $this->dbo->setQuery($query)
            ->loadResult();

        // Check if avatar exists.
        if (!$avatar) {
            return true;
        }

        $path = JPATH_SITE . '/' . $avatar;

        if (false !== strpos($path, 'default.jpg')) {
            return true;
        }

        if (!file_exists($path)) {
            return true;
        }

        // Copy photo to Love Factory folder.
        $userFolder = JPATH_SITE . '/media/com_lovefactory/storage/photos/' . $profile->user_id;

        if (!JFolder::exists($userFolder)) {
            JFolder::create($userFolder);
        }

        $explode = explode('/', $avatar);
        $filename = end($explode);

        JFile::copy($path, $userFolder . '/' . $filename);
        JFile::copy($path, $userFolder . '/thumb_' . $filename);

        // Create new Love Factory photo record from avatar.
        $table = JTable::getInstance('Photo', 'Table');

        $table->save(array(
            'user_id' => $profile->user_id,
            'filename' => $filename,
        ));

        // Mark Love Factory profile main photo.
        $profile->main_photo = $table->id;
        $profile->store();

        return true;
    }
}
