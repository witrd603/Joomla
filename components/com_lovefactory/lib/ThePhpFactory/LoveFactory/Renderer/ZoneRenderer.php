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

namespace ThePhpFactory\LoveFactory\Renderer;

defined('_JEXEC') or die;

class ZoneRenderer implements ZoneRendererInterface
{
    protected $columnRenderer;

    public function __construct(ColumnRendererInterface $columnRenderer)
    {
        $this->columnRenderer = $columnRenderer;
    }

    public function render($zone, $postZone, $data)
    {
        $html = array();

        $html[] = '<fieldset>';

        $html[] = '<legend>';
        $html[] = $zone['title'];
        $html[] = '</legend>';

        $html[] = '<div class="row-fluid form-horizontal">';

        foreach ($zone['columns'] as $columnId => $fields) {
            $width = isset($zone['width'][$columnId]) ? $zone['width'][$columnId] : 12 / count($zone['columns']);

            $html[] = $this->columnRenderer->render($fields, $width);
        }

        $html[] = '</div>';

        if ($postZone) {
            $html[] = $postZone->render($data);
        }

        $html[] = '</fieldset>';

        return implode("\n", $html);
    }
}
