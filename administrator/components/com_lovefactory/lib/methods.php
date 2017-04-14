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

jimport('joomla.application.component.view');
jimport('joomla.application.component.modellist');
jimport('joomla.html.pagination');

class FactoryRoute
{
    public static function _($url = '', $xhtml = false, $ssl = null)
    {
        if (JURI::getInstance()->isSSL()) {
            $ssl = 1;
        }

        $option = LoveFactoryApplication::getInstance()->getOption();
        $url = 'index.php?option=' . $option . ($url != '' ? '&' . $url : '');

        return JRoute::_($url, $xhtml, $ssl);
    }

    public static function view($view, $xhtml = false, $ssl = null)
    {
        $url = 'view=' . $view;

        return self::_($url, $xhtml, $ssl);
    }

    public static function task($task, $xhtml = false, $ssl = null)
    {
        if (false !== strpos($task, '.')) {
            list($controller, $task) = explode('.', $task);
            $task = $task . '&controller=' . $controller;
        }

        $url = 'task=' . $task;

        return self::_($url, $xhtml, $ssl);
    }
}

class FactoryText
{
    public static function _($string, $jsSafe = false, $interpretBackSlashes = true, $script = false)
    {
        $option = LoveFactoryApplication::getInstance()->getOption();
        $string = strtoupper($option . '_' . str_replace(' ', '_', $string));

        return JText::_($string, $jsSafe, $interpretBackSlashes, $script);
    }

    public static function sprintf()
    {
        $args = func_get_args();
        $option = LoveFactoryApplication::getInstance()->getOption();
        $args[0] = strtoupper($option . '_' . $args[0]);

        return call_user_func_array(array('JText', 'sprintf'), $args);
    }

    public static function script($string = null, $jsSafe = false, $interpretBackSlashes = true)
    {
        $option = LoveFactoryApplication::getInstance()->getOption();
        $string = strtoupper($option . '_' . $string);

        return JText::script($string, $jsSafe, $interpretBackSlashes);
    }

    public static function plural($string, $n)
    {
        $args = func_get_args();
        $option = LoveFactoryApplication::getInstance()->getOption();
        $args[0] = strtoupper($option . '_' . $args[0]);

        return call_user_func_array(array('JText', 'plural'), $args);
    }
}

class FactoryHtml
{
    static $framework_loaded = false;

    public static function script($file, $framework = true, $relative = false, $path_only = false, $detect_browser = true, $detect_debug = true)
    {
//    if ($framework && !self::$framework_loaded) {
//      self::script('jquery', false);
//      self::script('jquery.noconflict', false);
//
//      self::$framework_loaded = true;
//    }

        $file = self::parsePath($file);

        JHtml::script($file, false, $relative, $path_only, $detect_browser, $detect_debug);
    }

    public static function stylesheet($file, $attribs = array(), $relative = false, $path_only = false, $detect_browser = true, $detect_debug = true)
    {
        $file = self::parsePath($file, 'css');

        JHtml::stylesheet($file, $attribs, $relative, $path_only, $detect_browser, $detect_debug);
    }

    protected static function parsePath($file, $type = 'js')
    {
        $path = array();
        $parts = explode('/', $file);

        $path[] = 'components';
        $path[] = LoveFactoryApplication::getInstance()->getOption();

        if (isset($parts[0]) && 'admin' == $parts[0]) {
            array_unshift($path, 'administrator');
            unset($parts[0]);
            $parts = array_values($parts);
        }

        $path[] = 'assets';
        $path[] = $type;

        $count = count($parts);
        foreach ($parts as $i => $part) {
            if ($i + 1 == $count) {
                $path[] = $part . '.' . $type;
            } else {
                $path[] = $part;
            }
        }

        return implode('/', $path);
    }
}

class FactoryMailer
{
    protected $mailer;

    public function __construct()
    {
        $this->mailer = JFactory::getMailer();
    }

    public static function getInstance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    public function send($type, $receiverId, $variables = array(), $isHtml = true, $force = false)
    {
        // Skip notifications checks in case we are sending registration emails.
        if (!$force) {
            // Load user receiving profile.
            JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_lovefactory/tables');
            $profile = JTable::getInstance('Profile', 'Table');
            $profile->load($receiverId);

            $settings = JComponentHelper::getParams('com_lovefactory');
            $profile->setSettings($settings);

            // Check if user has enabled email notifications.
            if (!$profile->getParameter('email_notifications')) {
                return false;
            }
        }

        // Initialise variables.
        $receiver = JFactory::getUser($receiverId);
        $app = JFactory::getApplication();

        if (in_array($type, array('signup_without_activation', 'signup_with_admin_activation', 'signup_with_user_activation'))) {
            $receiverLanguage = JFactory::getLanguage()->getTag();
        } else {
            $receiverLanguage = $receiver->getParam('language', JComponentHelper::getParams('com_languages')->get('site'));
        }

        $receiverEmail = $receiver->email;
        $options = $this->getNotificationOptions($type);
        $notification = $this->getNotification($type, $receiverLanguage);

        // Check if notification was found.
        if (!$notification) {
            return false;
        }

        // Prepare subject and body.
        $subject = $this->parseVariables($notification->subject, array_keys($variables), array_values($variables));
        $body = $this->parseVariables($notification->body, array_keys($variables), array_values($variables));

        // Send mail.
        $this->mailer->setSubject($subject);
        $this->mailer->setBody($body);
        $this->mailer->ClearAddresses();
        $this->mailer->addRecipient($receiverEmail);
        $this->mailer->setSender(array($app->get('mailfrom'), $app->get('fromname')));
        $this->mailer->isHtml($isHtml);

        if (!$this->mailer->send()) {
            return false;
        }

        return true;
    }

    public function sendAdminNotification($type, $variables = array(), $isHtml = true)
    {
        // Get the receivers
        $notification = $this->getNotification($type, '*');

        if (!$notification) {
            return true;
        }

        $groups = new JRegistry($notification->groups);
        $groups = array_values($groups->toArray());

        if (!$groups) {
            return true;
        }

        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('DISTINCT(m.user_id)')
            ->from('#__user_usergroup_map m')
            ->where('m.group_id IN (' . implode(',', $groups) . ')');
        $results = $dbo->setQuery($query)
            ->loadObjectList('user_id');

        foreach (array_keys($results) as $result) {
            $app = JFactory::getApplication();
            $user = JFactory::getUser($result);
            $receiverEmail = $user->email;
            $options = $this->getNotificationOptions($type);

            array_unshift($variables, $user->username);

            // Prepare subject and body.
            $subject = $this->parseVariables($notification->subject, $options, $variables);
            $body = $this->parseVariables($notification->body, $options, $variables);

            // Send mail.
            $this->mailer->setSubject($subject);
            $this->mailer->setBody($body);
            $this->mailer->addRecipient($receiverEmail);
            $this->mailer->setSender(array($app->get('mailfrom'), $app->get('fromname')));
            $this->mailer->isHtml($isHtml);

            if (!$this->mailer->send()) {
                return false;
            }
        }
        return true;
    }

    protected function getNotificationOptions($type)
    {
        $options = array();
        $xml = simplexml_load_file(LoveFactoryApplication::getInstance()->getPath('component_administrator') . DS . 'notifications.xml');
        $notification = $xml->xpath('//notification[@type="' . $type . '"]');

        if (!$notification) {
            return $options;
        }

        foreach ($notification[0]->option as $option) {
            $options[] = (string)$option;
        }

        return $options;
    }

    protected function getNotification($type, $receiverLanguage)
    {
        $dbo = JFactory::getDbo();
        $query = $dbo->getQuery(true)
            ->select('n.*')
            ->from('#__lovefactory_notifications n')
            ->where('n.type = ' . $dbo->quote($type))
            ->where('n.published = ' . $dbo->quote(1))
            ->where('(n.lang_code = ' . $dbo->quote($receiverLanguage) . ' OR n.lang_code = ' . $dbo->quote('*') . ')');
        $notifications = $dbo->setQuery($query)
            ->loadObjectList('lang_code');

        if (!$notifications) {
            return false;
        }

        return isset($notifications[$receiverLanguage]) ? $notifications[$receiverLanguage] : $notifications['*'];
    }

    protected function parseVariables($string, $search, $replace)
    {
        $string = preg_replace('/%%(.+)%%/U', '{{ $1 }}', $string);

        // Prepare variables.
        foreach ($search as &$variable) {
            $variable = '{{ ' . $variable . ' }}';
        }

        // Replace variables.
        $string = str_replace($search, $replace, $string);

        // Replace image sources.
        $string = str_replace('src="', 'src="' . JURI::root(), $string);

        return $string;
    }
}

class FactoryFlashMessage
{
    protected $session;
    protected $message = null;
    protected $warning = null;

    public function __construct()
    {
        $this->session = JFactory::getSession();
    }

    public static function getInstance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    public function setMessage($message = null)
    {
        $this->session->set('com_lovefactory.flash.message', $message);
    }

    public function getMessage()
    {
        $message = $this->session->get('com_lovefactory.flash.message', null);

        $this->setMessage();

        return $message;
    }

    public function setWarning($warning = null)
    {
        $this->session->set('com_lovefactory.flash.warning', $warning);
    }

    public function getWarning()
    {
        $warning = $this->session->get('com_lovefactory.flash.warning', null);

        $this->setWarning();

        return $warning;
    }

    public function render()
    {
        $message = $this->getMessage();
        $warning = $this->getWarning();

        if (!is_null($message)) {
            JFactory::getApplication()->enqueueMessage($message, 'message');
        }

        if (!is_null($warning)) {
            JFactory::getApplication()->enqueueMessage($warning, 'error');
        }
    }
}

class FactoryView extends JViewLegacy
{
    protected
        $buttons = array(),
        $css = array(),
        $js = array(),
        $title = 'title',
        $id = 'id',
        $behaviors = array(),
        $get = array(),
        $html = array(),
        $jtexts = array(),
        $tpl = null,
        $javascriptVariables = array(),
        $routes = array(),
        $extraTplViewPaths = array();

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $this->loadAssets();

        if ($app->isSite()) {
            $smarty = LoveFactoryApplication::getInstance()->getSmarty($this->getName());

            // Assign variables.
            foreach ($this->get as $get) {
                $method = 'get' . ucfirst($get);
                $value = method_exists($this, $method) ? $this->$method() : $this->get($get);
                $smarty->assign($get, $value);

                $this->$get = $value;
            }

            // Assign view name.
            $smarty->assign('viewName', $this->getName());

            $layout = $this->getLayout();
            if (!is_null($this->tpl)) {
                $layout .= '_' . $this->tpl;
            }

            $path = LoveFactoryApplication::getInstance()->getPath('component') . DS . 'views' . DS . $this->getName() . DS . 'tmpl';

            // Check if layout exists
//      jimport('joomla.filesystem.file');
//      if (!JFile::exists($path.DS.$layout.'.tpl')) {
//        $layout = 'default';
//      }

            $smarty->addPluginsDir(JPATH_SITE . '/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins');

            $smarty
                ->addTemplateDir(JPATH_THEMES . '/' . $app->getTemplate() . '/html/com_lovefactory/' . $this->getName())
                ->addTemplateDir(LoveFactoryApplication::getInstance()->getPath('component') . '/views')
                ->addTemplateDir($path);

            foreach ($this->extraTplViewPaths as $view) {
                $smarty
                    ->addTemplateDir(JPATH_THEMES . '/' . $app->getTemplate() . '/html/com_lovefactory/' . $view)
                    ->addTemplateDir(LoveFactoryApplication::getInstance()->getPath('component') . DS . 'views' . DS . $view . DS . 'tmpl');
            }

            $smarty->display($layout . '.tpl');
        } else {
            foreach ($this->get as $get) {
                $method = 'get' . ucfirst($get);
                $value = 'getForm' != $method && method_exists($this, $method) ? $this->$method() : $this->get($get);

                $this->$get = $value;
            }

            $this->sidebar = JHtmlSidebar::render();

            ob_start();
            parent::display($tpl);
            $contents = ob_get_contents();
            ob_end_clean();

            $html = array();

            $html[] = '<div id="j-sidebar-container" class="span2">';
            $html[] = $this->sidebar;
            $html[] = '</div>';
            $html[] = '<div id="j-main-container" class="span10">';
            $html[] = $contents;
            $html[] = '</div>';

            echo implode("\n", $html);

            $viewHelp = new LoveFactoryViewHelp();
            $viewHelp->render($this->getName());
        }

        $this->addToolbar();

        $this->postDisplay();

        $this->setMetaData();

        return true;
    }

    public function render($tpl = null)
    {
        ob_start();
        $this->display($tpl);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    protected function loadAssets()
    {
        // Initialise variables.
        $name = $this->getName();
        $factoryApp = LoveFactoryApplication::getInstance();
        $prefix = JFactory::getApplication()->isAdmin() ? 'admin/' : '';

        JHtml::stylesheet('components/com_lovefactory/assets/font-awesome/css/font-awesome.min.css');

        JHtml::_('jquery.framework');

        if (!$factoryApp->getSettings()->bootstrap_template) {
            JHtml::_('bootstrap.loadCss', true, JFactory::getDocument()->getDirection());
        }

        // Load behaviors.
        foreach ($this->behaviors as $behavior) {
            if (0 === strpos($behavior, 'factory')) {
                $behavior = str_replace('factory', '', $behavior);
                JHtml::_('FactoryFramework.behavior', $behavior);
            } else {
                JHtml::_('behavior.' . $behavior);
            }
        }

        // Load CSS files.
        if ('rtl' === JFactory::getDocument()->getDirection()) {
            array_unshift($this->css, 'main_rtl');
        }

        array_unshift($this->css, 'icons');
        array_unshift($this->css, 'main');

        if (JFactory::getApplication()->isAdmin()) {
            $this->css[] = $prefix . 'views/' . strtolower($this->getName());
        }

        $this->css[] = 'override';

        foreach ($this->css as $css) {
            FactoryHtml::stylesheet($css);
        }

        // Load Javascript files.
        array_unshift($this->js, 'main');

        $this->js[] = $prefix . 'views/' . strtolower($this->getName());
        foreach ($this->js as $js) {
            FactoryHtml::script($js);
        }

        // Register default component Html helper.
        JLoader::register(
            'JHtml' . $factoryApp->getComponent(),
            $factoryApp->getPath('component_site') . DS . 'lib' . DS . 'html' . DS . 'html.php');

        // Register default view Html helper.
        JLoader::register(
            'JHtml' . $factoryApp->getComponent() . ucfirst($name),
            $factoryApp->getPath('component') . DS . 'html' . DS . strtolower($name) . '.php');

        // Register specified Html helpers
        foreach ($this->html as $html) {
            $location = 'component';

            if (false !== strpos($html, '/')) {
                list($suffix, $html) = explode('/', $html);
                $location .= '_' . $suffix;
            }

            JLoader::register(
                'JHtml' . $factoryApp->getComponent() . ucfirst($html),
                $factoryApp->getPath($location) . DS . 'html' . DS . strtolower($html) . '.php');
        }

        // Render JTexts
        if ($this->jtexts) {
            foreach ($this->jtexts as $jtext) {
                JText::script($jtext);
            }

            JHtml::_('behavior.framework');
        }

        // Render javascript variables.
        $javascript = array();

        // Prepare javascript variables.
        if (!is_null($this->javascriptVariables) && $this->javascriptVariables) {
            foreach ($this->javascriptVariables as $variable) {
                $javascript[$variable] = $this->get($variable);
            }
        }

        // Prepare javascript routes.
        if (!is_null($this->routes) && $this->routes) {
            foreach ($this->routes as $route) {
                list ($name, $type, $route) = explode('/', $route);
                $javascript['route' . $name] = FactoryRoute::$type($route, false, -1);
            }
        }

        if ($javascript) {
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('LoveFactory.set(' . json_encode($javascript) . ');');
        }
    }

    protected function addToolbar()
    {
        if (!JFactory::getApplication()->isAdmin()) {
            return false;
        }

        // Set title
        $this->setTitle();

        // Add buttons
        foreach ($this->buttons as $button) {
            switch ($button) {
                case '':
                    JToolBarHelper::divider();
                    break;

                case 'add':
                    JToolBarHelper::addNew(rtrim($this->getName(), 's') . '.add');
                    break;

                case 'edit':
                    JToolBarHelper::editList(rtrim($this->getName(), 's') . '.edit');
                    break;

                case 'publish':
                    JToolBarHelper::publishList($this->getName() . '.publish');
                    break;

                case 'unpublish':
                    JToolBarHelper::unpublishList($this->getName() . '.unpublish');
                    break;

                case 'delete':
                    JToolBarHelper::deleteList(FactoryText::_('list_delete'), $this->getName() . '.delete');
                    break;

                case 'apply':
                    JToolBarHelper::apply($this->getName() . '.apply');
                    break;

                case 'save':
                    JToolBarHelper::save($this->getName() . '.save');
                    break;

                case 'save2new':
                    JToolBarHelper::save2new($this->getName() . '.save2new');
                    break;

                case 'save2copy':
                    JToolBarHelper::save2copy($this->getName() . '.save2copy');
                    break;

                case 'close':
                    JToolBarHelper::cancel($this->getName() . '.cancel', (isset($this->item) && $this->item->{$this->id} ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL'));
                    break;

                case 'back':
                    JToolBarHelper::back();
                    break;

                default:
                    JToolBarHelper::custom($this->getName() . '.' . $button[0], $button[2], $button[2], FactoryText::_($button[1]), $button[3]);
                    break;
            }
        }

        // Set view icon.
        jimport('joomla.filesystem.file');
        $path = JPATH_COMPONENT_ADMINISTRATOR . DS . 'assets' . DS . 'images' . DS . 'views';
        $name = $this->getName() . '.png';

        if (JFile::exists($path . DS . $name)) {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.icon-48-generic {background-image: url("components/com_lovefactory/assets/images/views/' . $name . '");}');
        }

        return true;
    }

    protected function setTitle()
    {
        if (isset($this->item)) {
            if ($this->item->{$this->id}) {
                JToolBarHelper::title(FactoryText::sprintf('view_title_edit_' . $this->getName(), $this->item->{$this->title}, $this->item->{$this->id}));
            } else {
                JToolBarHelper::title(FactoryText::_('view_title_new_' . $this->getName()));
            }
        } else {
            JToolBarHelper::title(FactoryText::_('view_title_' . $this->getName()));
        }

        return true;
    }

    protected function postDisplay()
    {
    }

    protected function setMetaData()
    {
        if (JFactory::getApplication()->isAdmin()) {
            return false;
        }

        $document = JFactory::getDocument();
        $document->setDescription($this->getMetaDescription());
        $document->setMetaData('keywords', $this->getMetaKeywords());
    }

    protected function getMetaDescription()
    {
        $language = JFactory::getLanguage();

        $key = strtoupper('COM_LOVEFACTORY_META_DESCRIPTION_' . $this->getName());

        if ($language->hasKey($key)) {
            return $language->_($key);
        }

        return '';
    }

    protected function getMetaKeywords()
    {
        $language = JFactory::getLanguage();

        $key = strtoupper('COM_LOVEFACTORY_META_KEYWORDS_' . $this->getName());

        if ($language->hasKey($key)) {
            return $language->_($key);
        }

        return '';
    }
}

class JHtmlFactoryFramework
{
    public static function behavior($behavior)
    {
        $behavior = 'behavior' . $behavior;
        if (method_exists('JHtmlFactoryFramework', $behavior)) {
            self::$behavior();
        }
    }

    protected static function behaviorTooltip()
    {
        static $loaded = false;

        if (!$loaded) {
            $loaded = true;

            FactoryHtml::script('jquery.tipsy');
            FactoryHtml::script('jquery.tooltip');
            FactoryHtml::stylesheet('jquery.tipsy');
        }
    }

    protected static function behaviorDropDown()
    {
        static $loaded = false;

        if ($loaded) {
            return true;
        }

        FactoryHtml::script('jquery.dropdown');
        FactoryHtml::stylesheet('jquery.dropdown');

        $loaded = true;

        return true;
    }

    protected static function behaviorjQueryUI()
    {
        static $loaded = false;

        if ($loaded) {
            return true;
        }

        $theme = 'smoothness';
        //$theme = 'flick';
        //$theme = 'blitzer';

        FactoryHtml::script('jquery-ui');
        FactoryHtml::stylesheet('jqueryui/' . $theme . '/jquery-ui');
        FactoryHtml::stylesheet('jquery-ui');

        $loaded = true;

        return true;
    }

    protected static function behaviorjQueryCookie()
    {
        static $loaded = false;

        if ($loaded) {
            return true;
        }

        FactoryHtml::script('jquery.cookie');

        $loaded = true;

        return true;
    }

    protected static function behaviorAjaxAction()
    {
        static $framework_loaded = false;

        // Check if framework has been loaded.
        if (!$framework_loaded) {
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $.LoveFactoryAjaxAction(".lovefactory-ajax-action"); });');

            FactoryHtml::script('lovefactory');

            $framework_loaded = true;
        }

        return true;
    }

    protected static function behaviorCheckAll()
    {
        static $loaded = false;

        if (!$loaded) {
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $(".lovefactory-check-all").LoveFactoryCheckAll(); });');

            FactoryHtml::script('lovefactory');

            $loaded = true;
        }

        return true;
    }

    protected static function behaviorAutoGrow()
    {
        static $loaded = false;

        if (!$loaded) {
            $document = JFactory::getDocument();
            $document->addScriptDeclaration('jQuery(document).ready(function ($) { $("textarea.lovefactory-autogrow").autogrow(); });');

            FactoryHtml::script('jquery.autogrow');

            $loaded = true;
        }

        return true;
    }

    protected static function behaviorPrivacyButton()
    {
        static $loaded = false;

        if ($loaded) {
            return true;
        }

        FactoryHtml::script('lovefactory');
        FactoryHtml::script('jquery.privacy');
        FactoryHtml::stylesheet('jquery.privacy');

        $loaded = true;

        return true;
    }
}

class FactoryModelList extends JModelList
{
    protected $filters;
    protected $sort = array();
    protected $defaultOrder = 'asc';
    protected $_errors = array();

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->filters = JFactory::getApplication()->input->get('filter', array(), 'array');
    }

    public function getFilterSort()
    {
        $value = $this->getFilterValue('sort');
        $array = array();

        foreach ($this->sort as $val => $sort) {
            $array[] = array('value' => $val, 'text' => $sort['text']);
        }

        $select = JHtml::_(
            'select.genericlist',
            $array,
            'filter[sort]',
            '',
            'value',
            'text',
            $value
        );

        return $select;
    }

    public function getFilterOrder()
    {
        $value = $this->getFilterValue('order');

        $select = JHtml::_(
            'select.genericlist',
            array(
                ('asc' == $this->defaultOrder ? '' : 'asc') => FactoryText::_('list_filter_order_asc'),
                ('desc' == $this->defaultOrder ? '' : 'desc') => FactoryText::_('list_filter_order_desc'),
            ),
            'filter[order]',
            '',
            '',
            '',
            $value
        );

        return $select;
    }

    public function getFilterSearch()
    {
        $value = $this->getFilterValue('search');

        return '<input type="text" size="20" name="filter[search]" id="filtersearch" value="' . htmlentities($value) . '" />';
    }

    protected function getFilterValue($filter)
    {
        if (!isset($this->filters[$filter])) {
            return null;
        }

        return $this->filters[$filter];
    }

    protected function addOrder($query)
    {
        $sort = $this->getFilterValue('sort');
        $order = $this->getFilterValue('order');

        $sort = $this->sort[$sort]['column'];
        $order = in_array($order, array('asc', 'desc')) ? $order : $this->defaultOrder;

        $query->order($sort . ' ' . $order);
    }

    protected function getListQuery()
    {
        $query = parent::getListQuery();

        // Filter results.
        foreach (get_class_methods($this) as $method) {
            if (0 === strpos($method, 'getFilter')) {
                $filter = str_replace('getFilter', '', $method);
                $conditionFilter = 'addFilter' . ucfirst($filter) . 'Condition';

                if (method_exists($this, $conditionFilter)) {
                    $this->$conditionFilter($query);
                }
            }
        }

        // Order results.
        $this->addOrder($query);

        return $query;
    }

    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null) {
            // Default, return the last message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // If $i has been specified but does not exist, return false
            return false;
        } else {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString) {
            return (string)$error;
        }

        return $error;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setError($error)
    {
        array_push($this->_errors, $error);
    }

    /**
     * @return LoveFactoryTable
     */
    public function getTable($name = '', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options); // TODO: Change the autogenerated stub
    }
}

abstract class FactoryModelAdmin extends JModelAdmin
{
    protected $_errors = array();

    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null) {
            // Default, return the last message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // If $i has been specified but does not exist, return false
            return false;
        } else {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString) {
            return (string)$error;
        }

        return $error;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setError($error)
    {
        array_push($this->_errors, $error);
    }
}

class FactoryModel extends JModelLegacy
{
    protected $_errors = array();

    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null) {
            // Default, return the last message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // If $i has been specified but does not exist, return false
            return false;
        } else {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString) {
            return (string)$error;
        }

        return $error;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setError($error)
    {
        array_push($this->_errors, $error);
    }

    /**
     * @return LoveFactoryTable
     */
    public function getTable($name = '', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options); // TODO: Change the autogenerated stub
    }
}

class LoveFactoryPagination extends JPagination
{
    protected $anchor = false;

    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;
    }

    public function getAnchor()
    {
        return $this->anchor;
    }

    protected function _buildDataObject()
    {
        $data = parent::_buildDataObject();
        $anchor = $this->getAnchor();

        if ($anchor) {
            $data = $this->addAnchor($data, $anchor);
        }

        return $data;
    }

    protected function addAnchor($data, $anchor)
    {
        $items = array('all', 'start', 'previous', 'next', 'end');

        foreach ($items as $item) {
            $data->$item->link .= '#' . $anchor;
        }

        foreach ($data->pages as &$page) {
            $page->link .= '#' . $anchor;
        }

        return $data;
    }
}

class LoveFactoryTable extends JTable
{
    public $_errors = array();

    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null) {
            // Default, return the last message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // If $i has been specified but does not exist, return false
            return false;
        } else {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString) {
            return (string)$error;
        }

        return $error;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setError($error)
    {
        array_push($this->_errors, $error);
    }
}

class LoveFactoryAdminView extends JViewLegacy
{
    public function display($tpl = null)
    {
        $tmpl = JFactory::getApplication()->input->getCmd('tmpl');

        if ('component' == $tmpl) {
            return parent::display($tpl);
        }

        $viewHelp = new LoveFactoryViewHelp();
        $viewHelp->render($this->getName());

        $this->sidebar = JHtmlSidebar::render();

        ob_start();
        parent::display($tpl);
        $contents = ob_get_contents();
        ob_end_clean();

        $html = array();

        $html[] = '<div id="j-sidebar-container" class="span2">';
        $html[] = $this->sidebar;
        $html[] = '</div>';
        $html[] = '<div id="j-main-container" class="span10">';
        $html[] = $contents;
        $html[] = '</div>';

        echo implode("\n", $html);

        return true;
    }
}

class LoveFactoryFrontendModelList extends JModelList
{
    protected $_errors = array();

    public function getError($i = null, $toString = true)
    {
        // Find the error
        if ($i === null) {
            // Default, return the last message
            $error = end($this->_errors);
        } elseif (!array_key_exists($i, $this->_errors)) {
            // If $i has been specified but does not exist, return false
            return false;
        } else {
            $error = $this->_errors[$i];
        }

        // Check if only the string is requested
        if ($error instanceof Exception && $toString) {
            return (string)$error;
        }

        return $error;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setError($error)
    {
        array_push($this->_errors, $error);
    }

    /**
     * @return LoveFactoryTable
     */
    public function getTable($name = '', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options); // TODO: Change the autogenerated stub
    }
}

class LoveFactoryViewHelp
{
    protected $component;
    protected $override = 'http://wiki.thephpfactory.com/doku.php?id=joomla{major}0:{component}:{keyref}';
    protected $xpath = '//div[@class="dokuwiki"]/div[@class="page"]/div[@class="level1"]/ul/li/div[@class="li"]/a';
    protected $cache = 24;

    public function __construct(array $config = array())
    {
        if (isset($config['component'])) {
            $this->component = $config['component'];
        } else {
            $input = new JInput();
            $this->component = str_replace('com_', '', $input->getString('option'));
        }

        if (isset($config['override'])) {
            $this->override = $config['override'];
        }

        if (isset($config['xpath'])) {
            $this->xpath = $config['xpath'];
        }

        if (isset($config['cache'])) {
            $this->cache = $config['cache'];
        }
    }

    public function render($ref)
    {
        $pages = $this->getAvailablePages();

        if (!$pages || !in_array($ref, $pages)) {
            $ref = $this->component;
        }

        JToolbarHelper::help($ref, false, $this->override, $this->component);
    }

    protected function readUrl($url)
    {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');

        $hash = md5($url);
        $path = JPATH_ADMINISTRATOR . '/cache/com_' . $this->component;

        if (!JFolder::exists($path)) {
            JFolder::create($path);
        }

        if (!JFile::exists($path . '/' . $hash) || time() - 60 * 60 * $this->cache > filemtime($path . '/' . $hash)) {
            $data = $this->getUrl($url);

            file_put_contents($path . '/' . $hash, $data);
        } else {
            $data = file_get_contents($path . '/' . $hash);
        }

        return $data;
    }

    protected function parseHtml($html)
    {
        $pages = array();

        if ($html == strip_tags($html)) {
            return $pages;
        }

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_use_internal_errors(false);

        $xpath = new DOMXpath($doc);
        $items = $xpath->query($this->xpath);

        foreach ($items as $item) {
            /** @var DOMElement $item */
            $href = $item->getAttribute('href');
            $explode = explode(':', $href);
            $pages[] = end($explode);
        }

        return $pages;
    }

    protected function getAvailablePages()
    {
        $url = JHelp::createURL($this->component, false, $this->override, $this->component);
        $html = $this->readUrl($url);

        return $this->parseHtml($html);
    }

    protected function getUrl($url)
    {
        $data = $this->getUrlCurl($url);

        if (false !== $data) {
            return $data;
        }

        $data = $this->getUrlFileOpen($url);

        if (false !== $data) {
            return $data;
        }

        $data = $this->getUrlFSockOpen($url);

        return $data;
    }

    protected function getUrlCurl($url)
    {
        if (!function_exists('curl_init')) {
            return false;
        }

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 5,
        ));

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }

    protected function getUrlFileOpen($url)
    {
        if (!ini_get('allow_url_fopen')) {
            return false;
        }

        return file_get_contents($url);
    }

    protected function getUrlFSockOpen($url)
    {
        $uri = JUri::getInstance($url);
        $fp = fsockopen($uri->getHost(), 80, $errno, $errstr, 30);

        if (!$fp) {
            return false;
        }

        $data = array();
        $out = array(
            'GET ' . $uri->getPath() . $uri->getQuery() . ' HTTP/1.1' . "\r\n",
            'Host: ' . $uri->getHost() . "\r\n",
            'Connection: Close' . "\r\n\r\n",
        );

        fwrite($fp, implode($out));

        while (!feof($fp)) {
            $data[] = fgets($fp, 128);
        }

        fclose($fp);

        return implode($data);
    }
}
