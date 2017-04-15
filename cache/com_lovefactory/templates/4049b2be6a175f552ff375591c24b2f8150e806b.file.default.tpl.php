<?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 13:08:56
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\edit\tmpl\default.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3144258f1ff48bf2266-67887546%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4049b2be6a175f552ff375591c24b2f8150e806b' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\edit\\tmpl\\default.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    '936a24e0b62ffe77c112e5a196feb82ac8624ef2' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\layout.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3144258f1ff48bf2266-67887546',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'viewName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_58f1ff48d0a500_49274946',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58f1ff48d0a500_49274946')) {function content_58f1ff48d0a500_49274946($_smarty_tpl) {?><?php if (!is_callable('smarty_function_toolbar')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.toolbar.php';
if (!is_callable('smarty_function_text')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.text.php';
if (!is_callable('smarty_function_jroute')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jroute.php';
if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
if (!is_callable('smarty_function_jhtml')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jhtml.php';
?><div class="lovefactory-view view-<?php echo $_smarty_tpl->tpl_vars['viewName']->value;?>
">
    
    <?php echo smarty_function_toolbar(array('toolbar'=>"profile",'active'=>"profile"),$_smarty_tpl);?>



    <h1 class="heading"><?php echo smarty_function_text(array('text'=>("page_heading_title_").($_smarty_tpl->tpl_vars['viewName']->value)),$_smarty_tpl);?>
</h1>

    
    <form action="<?php echo smarty_function_jroute(array('raw'=>'index.php'),$_smarty_tpl);?>
" method="post" enctype="multipart/form-data">
        <?php echo $_smarty_tpl->tpl_vars['renderer']->value->render($_smarty_tpl->tpl_vars['page']->value);?>


        <div class="actions">
            <?php if ($_smarty_tpl->tpl_vars['settings']->value->approval_profile&&$_smarty_tpl->tpl_vars['profile']->value->isDraft){?>
                <button type="submit" onclick="document.getElementById('task').value = 'restore';"
                        class="btn btn-small btn-danger">
                    <span class="fa fa-fw fa-times"></span><?php echo smarty_function_text(array('text'=>'profile_edit_approval_restore_changes'),$_smarty_tpl);?>

                </button>
            <?php }?>

            <?php if ($_smarty_tpl->tpl_vars['settings']->value->approval_profile&&$_smarty_tpl->tpl_vars['profile']->value->isDraft&&!$_smarty_tpl->tpl_vars['profile']->value->isPending){?>
                <button type="submit" onclick="document.getElementById('task').value = 'submitapproval';"
                        class="btn btn-small btn-success">
                    <span class="fa fa-fw fa-check"></span><?php echo smarty_function_text(array('text'=>'profile_edit_approval_submit'),$_smarty_tpl);?>

                </button>
            <?php }?>

            <?php if (!$_smarty_tpl->tpl_vars['settings']->value->approval_profile||!$_smarty_tpl->tpl_vars['profile']->value->isDraft||!$_smarty_tpl->tpl_vars['profile']->value->isPending){?>
                <button type="submit" class="btn btn-small btn-primary">
                    <span class="fa fa-fw fa-check"></span><?php echo smarty_function_text(array('text'=>'profile_edit_submit_form'),$_smarty_tpl);?>

                </button>
                <?php if ($_smarty_tpl->tpl_vars['page']->value->hasRequiredFields()){?>
                    <div style="display: inline-block;">
                        <span class="required">*</span>&nbsp;<?php echo smarty_function_jtext(array('_'=>'profile_edit_required_fields_info'),$_smarty_tpl);?>

                    </div>
                <?php }?>
            <?php }?>
        </div>

        <input type="hidden" name="option" value="com_lovefactory"/>
        <input type="hidden" name="controller" value="profile"/>
        <input type="hidden" name="task" value="update" id="task"/>
        <?php echo smarty_function_jhtml(array('_'=>'form.token'),$_smarty_tpl);?>

    </form>

</div>
<?php }} ?>