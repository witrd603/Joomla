<?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 12:36:19
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\signup\tmpl\default_disabled.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2033558f1f7a3d0dbe9-62652479%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0282ac51b00e3e5b667c396a839cd852b6bbb443' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\signup\\tmpl\\default_disabled.tpl',
      1 => 1492189509,
      2 => 'file',
    ),
    '936a24e0b62ffe77c112e5a196feb82ac8624ef2' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\layout.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2033558f1f7a3d0dbe9-62652479',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'viewName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_58f1f7a3d97213_58059784',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58f1f7a3d97213_58059784')) {function content_58f1f7a3d97213_58059784($_smarty_tpl) {?><?php if (!is_callable('smarty_function_text')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.text.php';
if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
?><div class="lovefactory-view view-<?php echo $_smarty_tpl->tpl_vars['viewName']->value;?>
">
    

    <h1 class="heading"><?php echo smarty_function_text(array('text'=>("page_heading_title_").($_smarty_tpl->tpl_vars['viewName']->value)),$_smarty_tpl);?>
</h1>

    
    <p><?php echo smarty_function_jtext(array('_'=>"signup_disabled_information"),$_smarty_tpl);?>
</p>

</div>
<?php }} ?>