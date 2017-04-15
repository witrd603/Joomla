<?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 12:36:16
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\photos\tmpl\default.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2997158f1f7a0b0fef8-75841493%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a27fcf862c8cf4775e654db615ad3d4a8e4c0bd' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\photos\\tmpl\\default.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    '936a24e0b62ffe77c112e5a196feb82ac8624ef2' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\layout.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    'ff69f92ceadcf22e1948e1c5d855e616866cea4e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\photos\\tmpl\\photos.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2997158f1f7a0b0fef8-75841493',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'viewName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_58f1f7a0c8c427_68854669',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58f1f7a0c8c427_68854669')) {function content_58f1f7a0c8c427_68854669($_smarty_tpl) {?><?php if (!is_callable('smarty_function_toolbar')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.toolbar.php';
if (!is_callable('smarty_function_text')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.text.php';
if (!is_callable('smarty_function_route')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.route.php';
if (!is_callable('smarty_block_form')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\block.form.php';
if (!is_callable('smarty_function_jhtml')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jhtml.php';
?><div class="lovefactory-view view-<?php echo $_smarty_tpl->tpl_vars['viewName']->value;?>
">
    
    <?php echo smarty_function_toolbar(array('toolbar'=>"gallery"),$_smarty_tpl);?>



    <h1 class="heading"><?php echo smarty_function_text(array('text'=>("page_heading_title_").($_smarty_tpl->tpl_vars['viewName']->value)),$_smarty_tpl);?>
</h1>

    
    <?php ob_start();?><?php echo smarty_function_route(array('view'=>"photos"),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->smarty->_tag_stack[] = array('form', array('url'=>$_tmp1)); $_block_repeat=true; echo smarty_block_form(array('url'=>$_tmp1), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


    <?php if ($_smarty_tpl->tpl_vars['isMyGallery']->value){?>
        <div class="filters">
            <div style="float:left;">
                <a href="<?php echo smarty_function_route(array('view'=>"dialog&format=raw&layout=photoupload"),$_smarty_tpl);?>
<?php echo $_smarty_tpl->tpl_vars['test']->value;?>
"
                   class="photos-upload btn btn-small btn-primary">
                    <span class="fa fa-fw fa-plus"></span><?php echo smarty_function_text(array('text'=>'photos_add_photos'),$_smarty_tpl);?>

                </a>

                <?php if ($_smarty_tpl->tpl_vars['gravatar']->value){?>
                    <a href="<?php echo smarty_function_route(array('controller'=>"photo",'task'=>"addgravatar"),$_smarty_tpl);?>
" class="btn btn-small">
                        <span class="fa fa-fw fa-plus"></span><?php echo smarty_function_text(array('text'=>'photos_add_photo_gravatar'),$_smarty_tpl);?>

                    </a>
                <?php }?>
            </div>

            <?php echo $_smarty_tpl->tpl_vars['filterPrivacy']->value;?>

        </div>
    <?php }?>
        <ul class="upload-status" style="display: none;"></ul>
        <div class="privacy-status alert alert-error" style="display: none;"></div>
        <div class="photos" style="float: left; clear: both;">
            <?php /*  Call merged included template "photos.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('photos.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '2997158f1f7a0b0fef8-75841493');
content_58f1f7a0be6101_17212003($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "photos.tpl" */?>
        </div>
        <div style="clear: both;"></div>
    <?php if ($_smarty_tpl->tpl_vars['isMyGallery']->value){?>
        <?php if ($_smarty_tpl->tpl_vars['approval']->value){?>
            <div class="small muted">
                <span class="fa fa-fw fa-warning text-danger"></span><?php echo smarty_function_text(array('text'=>'photos_pending_approval_info'),$_smarty_tpl);?>

            </div>
        <?php }?>
        <div class="check-all-container"
             style="margin: 10px 0; display: <?php echo $_smarty_tpl->tpl_vars['items']->value ? 'block' : 'none';?>
"><?php echo smarty_function_jhtml(array('_'=>'LoveFactory.checkAll'),$_smarty_tpl);?>
</div>
        <div class="actions" style="display: <?php echo $_smarty_tpl->tpl_vars['items']->value ? 'block' : 'none';?>
">
            <span class="batch-label hidden-phone"><?php echo smarty_function_text(array('text'=>'batch_actions_label'),$_smarty_tpl);?>
</span>

            <button type="button" class="batch-delete btn btn-small btn-danger">
                <span class="fa fa-fw fa-times"></span><?php echo smarty_function_text(array('text'=>'batch_actions_delete'),$_smarty_tpl);?>

            </button>

            <button type="button" class="photo-set-main btn btn-small">
                <span class="fa fa-fw fa-user"></span><?php echo smarty_function_text(array('text'=>'photos_actions_button_set_profile_photo'),$_smarty_tpl);?>

            </button>

            <span class="batch-label hidden-phone"
                  style="margin-right: 5px;"><?php echo smarty_function_text(array('text'=>'videos_batch_change_privacy'),$_smarty_tpl);?>
</span><?php echo smarty_function_jhtml(array('_'=>'LoveFactoryPhotos.privacyButton'),$_smarty_tpl);?>

        </div>
    <?php }?>

    <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_form(array('url'=>$_tmp1), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>


</div>
<?php }} ?><?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 12:36:16
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\photos\tmpl\photos.tpl" */ ?>
<?php if ($_valid && !is_callable('content_58f1f7a0be6101_17212003')) {function content_58f1f7a0be6101_17212003($_smarty_tpl) {?><?php if (!is_callable('smarty_function_jroute')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jroute.php';
if (!is_callable('smarty_function_jhtml')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jhtml.php';
if (!is_callable('smarty_function_route')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.route.php';
if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
?><?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
    <div class="photo" id="photo-<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
">
        <?php if ($_smarty_tpl->tpl_vars['isMyGallery']->value){?>
            <div class="shader"></div>
            <input type="checkbox" name="batch[]" value="<?php echo $_smarty_tpl->tpl_vars['item']->value->id;?>
"/>
            <span class="move-handle"><i class="factory-icon icon-arrow-move"></i></span>
            <?php if ($_smarty_tpl->tpl_vars['item']->value->id==$_smarty_tpl->tpl_vars['user']->value->main_photo){?>
                <i class="factory-icon icon-star profile-photo"></i>
            <?php }?>
        <?php }?>

        <a href="<?php echo smarty_function_jroute(array('view'=>('photo&id=').($_smarty_tpl->tpl_vars['item']->value->id)),$_smarty_tpl);?>
" class="thumbnail"
           style="background-image: url(<?php echo $_smarty_tpl->tpl_vars['item']->value->getSource(true);?>
);"></a>

        <div class="info">
            <?php if ($_smarty_tpl->tpl_vars['isMyGallery']->value){?>
                <?php echo smarty_function_jhtml(array('_'=>'LoveFactoryPhotos.privacyButton','privacy'=>$_smarty_tpl->tpl_vars['item']->value->status),$_smarty_tpl);?>

            <?php }?>

            <?php if ($_smarty_tpl->tpl_vars['approval']->value&&!$_smarty_tpl->tpl_vars['item']->value->approved){?>
                <span class="fa fa-fw fa-warning text-danger"></span>
            <?php }?>

            <?php if ($_smarty_tpl->tpl_vars['item']->value->comments){?>
                <a href="<?php echo smarty_function_route(array('view'=>"photo",'id'=>$_smarty_tpl->tpl_vars['item']->value->id),$_smarty_tpl);?>
#comments" class="muted">
                    <span class="fa fa-fw fa-comment"></span><?php echo $_smarty_tpl->tpl_vars['item']->value->comments;?>
</a>
            <?php }?>
        </div>

    </div>
    <?php }
if (!$_smarty_tpl->tpl_vars['item']->_loop) {
?>
    <div class="no-items">
        <?php echo smarty_function_jtext(array('_'=>'photos_no_items_found'),$_smarty_tpl);?>

    </div>
<?php } ?>
<?php }} ?>