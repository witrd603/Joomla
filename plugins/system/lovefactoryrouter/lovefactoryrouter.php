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

class plgSystemLoveFactoryRouter extends JPlugin
{
    public function onAfterInitialise()
    {
        $app = JFactory::getApplication();

        // Not in the backend, it hurts.
        if ($app->isAdmin()) {
            return true;
        }

        // Not if SEF is disabled.
        if (!$app->get('sef')) {
            return true;
        }

        // Autoload classes.
        $this->autoload();

        // Initialise variables.
        $segments = $this->params->get('segments', array());
        $firstSegmentLang = $this->params->get('first_segment_lang', 0);
        $menuItemCheck = $this->params->get('menu_item_check', 0);

        if (!$segments) {
            return true;
        }

        $builder = new LoveFactoryRouterBuilder($segments, $firstSegmentLang);
        $parser = new LoveFactoryRouterParser($segments, $menuItemCheck);

        // Get router.
        $router = $app->getRouter();

        // Attach rules.
        $router->attachBuildRule(array($builder, 'build'));
        $router->attachParseRule(array($parser, 'parse'));

        return true;
    }

    public function onExtensionBeforeSave($context, $table, $isNew)
    {
        if ('com_plugins.plugin' !== $context) {
            return true;
        }

        if (!$table instanceof JTableExtension) {
            return true;
        }

        if ('plugin' !== $table->type ||
            'lovefactoryrouter' !== $table->element ||
            'system' !== $table->folder
        ) {
            return true;
        }

        $params = new \Joomla\Registry\Registry($table->params);
        $segments = $params->get('segments', array());

        foreach ($segments as $i => $segment) {
            if ('' === $segment) {
                unset($segments[$i]);
            }
        }

        if (!$segments) {
            $table->setError('You must select at least one segment!');
            return false;
        }

        $firstId = reset($segments);
        $type = $this->getFieldType($firstId);

        if ('Text' === $type) {
            $table->setError('That the first segment cannot be of type &quot;Text&quot;.');
            return false;
        }

        $params->set('segments', array_values($segments));
        $table->params = $params->toString();

        return true;
    }

    private function autoload()
    {
        JLoader::register('LoveFactoryRouterHelper', JPATH_PLUGINS . '/system/lovefactoryrouter/helper.php');
        JLoader::register('LoveFactoryRouterBuilder', JPATH_PLUGINS . '/system/lovefactoryrouter/router/builder.php');
        JLoader::register('LoveFactoryRouterParser', JPATH_PLUGINS . '/system/lovefactoryrouter/router/parser.php');
        JLoader::register('LoveFactoryApplication', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/application.php');
        JLoader::register('LoveFactoryField', JPATH_ADMINISTRATOR . '/components/com_lovefactory/lib/fields/field.php');
        JLoader::register('LoveFactoryPage', JPATH_SITE . '/components/com_lovefactory/lib/vendor/page.php');
        JLoader::register('LoveFactoryFieldSingleChoiceInterface', JPATH_SITE . '/components/com_lovefactory/lib/vendor/page.php');
        JLoader::register('LoveFactoryFieldMultipleChoiceInterface', JPATH_SITE . '/components/com_lovefactory/lib/vendor/page.php');
        JLoader::register('LoveFactoryFieldMultipleChoiceInterface', JPATH_SITE . '/components/com_lovefactory/lib/vendor/page.php');

        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/tables');
    }

    private function getFieldType($id)
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('f.type')
            ->from($dbo->qn('#__lovefactory_fields', 'f'))
            ->where('f.id = ' . $dbo->q($id));

        $result = $dbo->setQuery($query)
            ->loadResult();

        return $result;
    }
}
