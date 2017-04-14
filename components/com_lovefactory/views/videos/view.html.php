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

class FrontendViewVideos extends FactoryView
{
    protected
        $get = array(
        'profile',
        'items',
        'isMyGallery',
        'filterPrivacy',
        'approval',
    ),
        $js = array('jquery.tipsy', 'lovefactory'),
        $css = array('jquery.tipsy'),
        $behaviors = array('factoryJQueryUI', 'factoryPrivacyButton', 'factoryTooltip'),
        $routes = array(
        'video.setPrivacy/task/privacy.setPrivacy',
        'videos.saveOrder/task/videos.saveorder',
        'videos.delete/task/videos.delete',
        'video.retrieveYoutubeData/task/video.getyoutubedata',
    );
}
