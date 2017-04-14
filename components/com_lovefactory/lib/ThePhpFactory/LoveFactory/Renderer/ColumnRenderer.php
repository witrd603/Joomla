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

class ColumnRenderer implements ColumnRendererInterface
{
    protected $fieldRenderer;

    public function __construct(FieldRendererInterface $fieldRenderer)
    {
        $this->fieldRenderer = $fieldRenderer;
    }

    public function render($fields, $width = 4)
    {
        $html = array();

        $html[] = '<div class="span' . $width . ' clearfix">';

        foreach ($fields as $field) {
            $html[] = $this->fieldRenderer->render($field);
        }

        $html[] = '</div>';

        return implode("\n", $html);
    }
}
