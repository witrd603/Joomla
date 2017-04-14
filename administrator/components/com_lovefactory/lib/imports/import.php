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

abstract class LoveFactoryImport
{
    protected $adaptor;
    protected $name;
    protected $job;
    protected $attributes;
    protected $params;
    protected $startActionTime;
    protected $maxMemory;
    protected $maxTime;
    protected $deltaMemory = 5;
    protected $deltaTime = 5;
    protected $dbo;
    protected $actions = array();

    public function __construct($adaptor)
    {
        $this->adaptor = $adaptor;
        $this->xml = simplexml_load_file(__DIR__ . '/adaptors/' . $adaptor . '.xml');
        $this->maxMemory = intval(ini_get('memory_limit'));
        $this->maxTime = intval(ini_get('max_execution_time'));
        $this->dbo = JFactory::getDbo();

        $this->parseActions();
    }

    public static function getInstance($adaptor)
    {
        $class = 'LoveFactoryImport' . ucfirst($adaptor);

        if (!class_exists($class)) {
            $file = __DIR__ . '/adaptors/' . $adaptor . '.php';

            if (!file_exists($file)) {
                return false;
            }

            require_once($file);
        }

        if (!class_exists($class)) {
            return false;
        }

        return new $class($adaptor);
    }

    public function import()
    {
        // Initialise variables.
        $response = array('status' => 0);
        $this->job = $this->getJob();
        $this->attributes = $this->getActionAttributes($this->job->current_action);
        $this->params = new JRegistry($this->job->params);

        // Crete method name.
        $method = 'action' . ucfirst(str_replace('.', '', $this->job->current_action));

        // Check if method exists.
        if (!method_exists($this, $method)) {
            $response['message'] = 'Method does not exist! ' . $method;
            return $response;
        }

        // Mark time before executing action.
        $this->startActionTime = microtime(true);

        // Execute action.
        if (!$this->$method()) {
            return $response;
        }

        $actionPercent = $this->attributes->get('percent', 100 / (count($this->actions) + 1));

        if ($this->job->current_action_finished) {
            $next = $this->getNextAction();

            $this->job->last_action = $this->job->current_action;
            $this->job->current_action = $next;
            $this->job->current_action_percent = 0;
            $this->job->current_action_finished = 0;

            $this->job->percent += $actionPercent;

            if (!$next) {
                $this->job->finished = 1;
                $this->job->percent = 100;

                $response['message'] = 'Finished.';
            } else {
                $attributes = $this->getActionAttributes($next);
                $response['message'] = $attributes->get('label', $next);
            }

            $percent = $this->job->percent;
        } else {
            $percent = $this->job->percent + $actionPercent * $this->job->current_action_percent / 100;
            $response['message'] = $this->attributes->get('label', $this->job->current_action) . ' <span class="muted">&mdash; ' . number_format($this->job->current_action_percent, 2) . '%</span>';
        }

        $response['status'] = 1;

        $response['finished'] = (boolean)$this->job->finished;
        $response['percent'] = $percent;

        $this->job->params = $this->params->toString();
        $this->job->store();

        return $response;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAdaptor()
    {
        return $this->adaptor;
    }

    public function setParam($param, $value = null)
    {
        $this->params->set($param, $value);
    }

    public function getParam($param, $default = null)
    {
        return $this->params->get($param, $default);
    }

    public function getAttribute($attribute, $default = null)
    {
        return $this->attributes->get($attribute, $default);
    }

    public function actionInit()
    {
        $this->finishAction();

        return true;
    }

    public function getCurrentJob()
    {
        $table = JTable::getInstance('Import', 'LoveFactoryTable');
        $data = array('adaptor' => $this->adaptor, 'finished' => 0);

        return $table->load($data);
    }

    public function getXml()
    {
        return $this->xml;
    }

    protected function getJob()
    {
        if (is_null($this->job)) {
            $this->job = false;

            $table = JTable::getInstance('Import', 'LoveFactoryTable');
            $data = array('adaptor' => $this->adaptor, 'finished' => 0);

            if (!$table->load($data)) {
                $data['current_action'] = 'init';
                $table->save($data);
            }

            $this->job = $table;
        }

        return $this->job;
    }

    protected function parseActions()
    {
        $actions = $this->xml->xpath('//actions/action');

        foreach ($actions as $action) {
            $attributes = array();
            foreach ($action->attributes() as $name => $value) {
                $attributes[$name] = (string)$value;
            }

            $this->actions[] = array('action' => (string)$action, 'attributes' => new JRegistry($attributes));
        }
    }

    protected function getNextAction()
    {
        $current = $this->job->current_action;
        $next = false;

        if ('init' == $current) {
            return $this->actions[0]['action'];
        }

        foreach ($this->actions as $id => $action) {
            if ($current == $action['action'] && isset($this->actions[$id + 1])) {
                $next = $this->actions[$id + 1]['action'];
            }
        }

        return $next;
    }

    protected function getActionAttributes()
    {
        foreach ($this->actions as $action) {
            if ($action['action'] == $this->job->current_action) {
                return $action['attributes'];
            }
        }

        return new JRegistry();
    }

    protected function truncateTable($table)
    {
        $query = ' TRUNCATE TABLE ' . $this->dbo->quoteName($table);

        return $this->dbo->setQuery($query)->execute();
    }

    protected function finishAction()
    {
        $this->job->current_action_finished = 1;

        return true;
    }

    protected function setActionPercent($percent)
    {
        $this->job->current_action_percent = $percent;
    }

    protected function areResourcesAvailable()
    {
        $memory = ceil(memory_get_usage() / 1048576);
        $peak = ceil(memory_get_peak_usage() / 1048576);
        $time = microtime(true);

        //file_put_contents('info.txt', 'Peak: ' . $peak . ', Memory: ' . $memory . ', Time: ' . ceil($time - $this->startActionTime) . "\n", FILE_APPEND);

        // Check peak memory.
        if ($this->maxMemory - $peak < $this->deltaMemory) {
            return false;
        }

        // Check current used memory.
        if ($this->maxMemory - $memory < $this->deltaMemory) {
            return false;
        }

        // Check script execution time.
        if (ceil($time - $this->startActionTime) + $this->deltaTime > $this->maxTime) {
            return false;
        }

        return true;
    }

    protected function prepareValues($values)
    {
        $dbo = JFactory::getDbo();

        foreach ($values as $key => $value) {
            $values[$key] = $dbo->quote($value);
        }

        return '(' . implode(', ', $values) . ')';
    }

    protected function prepareColumns($columns)
    {
        $dbo = JFactory::getDbo();

        foreach ($columns as $key => $column) {
            $columns[$key] = $dbo->quoteName($column);
        }

        return '(' . implode(', ', $columns) . ')';
    }
}

class LoveFactoryTableImport extends JTable
{
    public function __construct($dbo)
    {
        parent::__construct('#__lovefactory_imports', 'id', $dbo);
    }
}
