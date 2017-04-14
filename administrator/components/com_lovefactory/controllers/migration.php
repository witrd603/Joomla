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

class BackendControllerMigration extends BackendController
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/models/migrations');
    }

    public function membershipSoldRestrictions()
    {
        JToolbarHelper::title('Updating database');

        $url = 'index.php?option=com_lovefactory&controller=migration&task=rawmembershipsoldrestrictions';
        $redirect = 'index.php?option=com_lovefactory&controller=migration&task=redirectmembershipsoldrestrictions';
        $uri = JUri::root();

        echo '<div>Please wait...</div>';
        echo '<div><img src="' . $uri . 'components/com_lovefactory/assets/images/loadingAnimation.gif" /></div>';

        JFactory::getDocument()->addScriptDeclaration(
            <<<JS
            jQuery(document).ready(function ($) {
  function update() {
    $.get('$url', function (response) {
      if (response.status) {
        if (!response.finished) {
          update();
        }
        else {
          window.location.href = '$redirect';
        }
      }
    }, 'json');
  }

  update();
});
JS
        );
    }

    public function rawMembershipSoldRestrictions()
    {
        $model = $this->getModel('MembershipSoldRestrictions');
        $response = array(
            'status' => 1,
        );

        try {
            $finished = $model->migrate();

            $response['finished'] = $finished;

            if ($finished) {
                jimport('joomla.filesystem.file');
                JFile::delete(JPATH_ADMINISTRATOR . '/components/com_lovefactory/migrations.php');
            }
        } catch (Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);

        jexit();
    }

    public function redirectMembershipSoldRestrictions()
    {
        $this->setMessage('Database updated successfully!');
        $this->setRedirect('index.php?option=com_lovefactory&view=dashboard');
    }
}
