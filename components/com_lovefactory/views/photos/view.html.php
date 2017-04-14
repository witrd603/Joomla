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

class FrontendViewPhotos extends FactoryView
{
    protected
        $get = array(
        'profile',
        'items',
        'isMyGallery',
        'filterPrivacy',
        'approval',
        'user',
        'gravatar',
        'test',
    ),
        $js = array('jquery.tipsy', 'lovefactory'),
        $css = array('jquery.tipsy'),
        $behaviors = array('factoryJQueryUI', 'factoryPrivacyButton', 'factoryTooltip'),
        $javascriptVariables = array('routeSetProfilePhoto'),
        $routes = array(
        'photosSaveOrder/task/photos.saveorder',
        'photosSetPrivacy/task/photos.setprivacy',
        'photoDelete/task/photos.delete',
        'photoUpload/task/photo.upload',
        'photosUpdate/view/photos&format=raw&layout=photos',
        'setProfilePhoto/task/photo.setMain',
        'setPrivacy/task/privacy.setPrivacy'
    );
}
