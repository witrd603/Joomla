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

namespace ThePhpFactory\LoveFactory;

defined('_JEXEC') or die;

use ThePhpFactory\LoveFactory\Renderer\PostZone\Results;
use ThePhpFactory\LoveFactory\Renderer\PostZone\Requests;
use ThePhpFactory\LoveFactory\Renderer\PostZone\MyFriends;
use ThePhpFactory\LoveFactory\Renderer\PostZone\Relationship;
use ThePhpFactory\LoveFactory\Renderer\ZoneRenderer;
use ThePhpFactory\LoveFactory\Renderer\ColumnRenderer;
use ThePhpFactory\LoveFactory\Renderer\EditableFieldRenderer;
use ThePhpFactory\LoveFactory\Renderer\ViewableFieldRenderer;
use ThePhpFactory\LoveFactory\Renderer\SearchableFieldRenderer;
use ThePhpFactory\LoveFactory\Renderer\PageRenderer;

class Factory
{
    public static function buildPageRenderer($mode = 'editable')
    {
        $fieldRenderer = self::buildFieldRenderer($mode);
        $columnRenderer = new ColumnRenderer($fieldRenderer);
        $zoneRenderer = new ZoneRenderer($columnRenderer);
        $renderer = new PageRenderer($zoneRenderer);

        return $renderer;
    }

    public static function buildFieldRenderer($mode = 'editable')
    {
        if ('viewable' == $mode) {
            return new ViewableFieldRenderer();
        }

        if ('searchable' === $mode) {
            return new SearchableFieldRenderer();
        }

        return new EditableFieldRenderer();
    }

    public static function buildPostZoneResults()
    {
        return new Results();
    }

    public static function buildPostZoneRequests($settings)
    {
        return new Requests($settings);
    }

    public static function buildPostZoneMyFriends()
    {
        return new MyFriends();
    }

    public static function buildPostZoneRelationship()
    {
        return new Relationship();
    }
}
