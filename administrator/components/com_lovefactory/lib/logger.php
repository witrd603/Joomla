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

class Logger
{
    protected $file;

    public function __construct()
    {
        $this->file = JPATH_ADMINISTRATOR . '/components/com_lovefactory/cron_log.php';
    }

    public function log($message, $spacer = false)
    {
        if (!file_exists($this->file)) {
            file_put_contents($this->file, '<?php defined(\'_JEXEC\') or die; ?>');
        }

        if ($spacer) {
            file_put_contents($this->file, str_repeat(PHP_EOL, 1), FILE_APPEND);
        }

        file_put_contents($this->file, PHP_EOL . '[' . JFactory::getDate()->toSql() . '] ' . $message, FILE_APPEND);
    }
}
