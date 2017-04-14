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

use LoveFactoryPage;

class PageRenderer
{
    protected $zoneRenderer;
    protected $postZone = null;

    public function __construct($zoneRenderer)
    {
        $this->zoneRenderer = $zoneRenderer;
    }

    public function render(LoveFactoryPage $page, $data = null)
    {
        if (null !== $data) {
            $page->bind($data);
        }

        $html = array();

        $html[] = '<div class="lovefactory-page">';

        foreach ($page->getZones() as $zone) {
            $html[] = $this->zoneRenderer->render($zone, $this->getPostZone(), $page->getData());
        }

        $html[] = '</div>';

        return implode("\n", $html);
    }

    public function getPostZone()
    {
        return $this->postZone;
    }

    public function setPostZone($postZone)
    {
        $this->postZone = $postZone;
    }
}
