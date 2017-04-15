<?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 12:52:16
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\signup\tmpl\default.tpl" */ ?>
<?php /*%%SmartyHeaderCode:261258f1fb60439405-07333639%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33102bc2d4a120725dd271a818431ba5bf54369f' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\signup\\tmpl\\default.tpl',
      1 => 1492189509,
      2 => 'file',
    ),
    '936a24e0b62ffe77c112e5a196feb82ac8624ef2' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\layout.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    '36937c79923741c84935f261f26cae651a8a2db2' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\signup\\tmpl\\_content.tpl',
      1 => 1492189509,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '261258f1fb60439405-07333639',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'viewName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_58f1fb605476b6_32765033',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58f1fb605476b6_32765033')) {function content_58f1fb605476b6_32765033($_smarty_tpl) {?><?php if (!is_callable('smarty_function_text')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.text.php';
?><div class="lovefactory-view view-<?php echo $_smarty_tpl->tpl_vars['viewName']->value;?>
">
    

    <h1 class="heading"><?php echo smarty_function_text(array('text'=>("page_heading_title_").($_smarty_tpl->tpl_vars['viewName']->value)),$_smarty_tpl);?>
</h1>

    
    <?php /*  Call merged included template "_content.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("_content.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '261258f1fb60439405-07333639');
content_58f1fb604c7362_92331219($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "_content.tpl" */?>

</div>
<?php }} ?><?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 12:52:16
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\signup\tmpl\_content.tpl" */ ?>
<?php if ($_valid && !is_callable('content_58f1fb604c7362_92331219')) {function content_58f1fb604c7362_92331219($_smarty_tpl) {?><?php if (!is_callable('smarty_function_jhtml')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jhtml.php';
if (!is_callable('smarty_function_jroute')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jroute.php';
if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
?><?php echo smarty_function_jhtml(array('_'=>"script",'file'=>"components/com_lovefactory/assets/js/views/signup.js"),$_smarty_tpl);?>


<form action="<?php echo smarty_function_jroute(array('raw'=>'index.php'),$_smarty_tpl);?>
" method="post" enctype="multipart/form-data">
    <?php echo $_smarty_tpl->tpl_vars['renderer']->value->render($_smarty_tpl->tpl_vars['page']->value);?>


    <div style="margin-top: 10px;">
        <button type="submit" class="btn btn-small btn-primary">
            <span class="fa fa-fw fa-check"></span>
            <?php if ($_smarty_tpl->tpl_vars['settings']->value->registration_membership){?>
                <?php echo smarty_function_jtext(array('_'=>'profile_signup_submit_form_and_membership'),$_smarty_tpl);?>

            <?php }else{ ?>
                <?php echo smarty_function_jtext(array('_'=>'profile_signup_submit_form'),$_smarty_tpl);?>

            <?php }?>

        </button>

        <?php if ($_smarty_tpl->tpl_vars['page']->value->hasRequiredFields()){?>
            <span class="required">*</span>
            &nbsp;<?php echo smarty_function_jtext(array('_'=>'profile_edit_required_fields_info'),$_smarty_tpl);?>

        <?php }?>
    </div>

    <input type="hidden" name="option" value="com_lovefactory"/>
    <input type="hidden" name="controller" value="signup"/>
    <input type="hidden" name="task" value="signup" id="task"/>
    <?php echo smarty_function_jhtml(array('_'=>'form.token'),$_smarty_tpl);?>

</form>
<?php }} ?>