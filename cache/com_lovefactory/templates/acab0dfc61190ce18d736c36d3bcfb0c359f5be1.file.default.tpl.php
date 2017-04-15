<?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 13:06:00
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\profile\tmpl\default.tpl" */ ?>
<?php /*%%SmartyHeaderCode:67758f1fe988bfd03-05663689%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'acab0dfc61190ce18d736c36d3bcfb0c359f5be1' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\profile\\tmpl\\default.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    '936a24e0b62ffe77c112e5a196feb82ac8624ef2' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\layout.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    '0457c19e6dfedc42be8dc319a4c8ef2656edca0b' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\profile\\tmpl\\default_user_status.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    'c8bd7b263016cf4de2014a31756f4a60d00d8990' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\profile\\tmpl\\default_login.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    'de86adea6711788a3fe1d5af826dd9eac4eeba0d' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\profile\\tmpl\\default_fillin.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    'c24813e71b604cc57584b04f946b8008632e2334' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\profile\\tmpl\\default_interact.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
    '1f3cb258c9c63cf7a15cab547fb5d83946a3fac1' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\profile\\tmpl\\default_ratings.tpl',
      1 => 1492189508,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '67758f1fe988bfd03-05663689',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'viewName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_58f1fe98b083c5_09382727',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58f1fe98b083c5_09382727')) {function content_58f1fe98b083c5_09382727($_smarty_tpl) {?><?php if (!is_callable('smarty_function_toolbar')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.toolbar.php';
if (!is_callable('smarty_function_text')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.text.php';
if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
?><div class="lovefactory-view view-<?php echo $_smarty_tpl->tpl_vars['viewName']->value;?>
">
    
    <?php echo smarty_function_toolbar(array('toolbar'=>"profile"),$_smarty_tpl);?>



    <h1 class="heading"></h1>

    
    <div class="row-fluid">
        <div class="span8">
            <?php if ($_smarty_tpl->tpl_vars['profile']->value->isMyProfile()&&$_smarty_tpl->tpl_vars['settings']->value->profile_status_change&&in_array($_smarty_tpl->tpl_vars['profile']->value->online,array(1,2))){?>
                <i class="factory-icon icon-exclamation-red"></i>
                <?php if (1==$_smarty_tpl->tpl_vars['profile']->value->online){?><?php echo smarty_function_jtext(array('_'=>'profile_status_friends_only'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smarty_function_jtext(array('_'=>'profile_status_offline'),$_smarty_tpl);?>
<?php }?>
            <?php }?>

            <?php echo $_smarty_tpl->tpl_vars['renderer']->value->render($_smarty_tpl->tpl_vars['page']->value);?>

        </div>

        <div class="span4">
            <?php if ($_smarty_tpl->tpl_vars['settings']->value->enable_status){?>
                <?php /*  Call merged included template "default_user_status.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default_user_status.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '67758f1fe988bfd03-05663689');
content_58f1fe9897da25_39032383($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default_user_status.tpl" */?>
            <?php }?>

            <?php /*  Call merged included template "default_interact.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default_interact.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '67758f1fe988bfd03-05663689');
content_58f1fe989aea55_24139309($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default_interact.tpl" */?>

            <?php if ($_smarty_tpl->tpl_vars['settings']->value->enable_rating){?>
                <?php /*  Call merged included template "default_ratings.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default_ratings.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '67758f1fe988bfd03-05663689');
content_58f1fe98a7d952_83324285($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default_ratings.tpl" */?>
            <?php }?>
        </div>
    </div>

</div>
<?php }} ?><?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 13:06:00
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\profile\tmpl\default_user_status.tpl" */ ?>
<?php if ($_valid && !is_callable('content_58f1fe9897da25_39032383')) {function content_58f1fe9897da25_39032383($_smarty_tpl) {?><?php if (!is_callable('smarty_function_text')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.text.php';
if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
?><?php if (($_smarty_tpl->tpl_vars['settings']->value->enable_status&&($_smarty_tpl->tpl_vars['profile']->value->isMyProfile()||$_smarty_tpl->tpl_vars['profile']->value->status))){?>
    <div class="well well-small status-update">
        <?php if ($_smarty_tpl->tpl_vars['profile']->value->isMyProfile()){?>
            <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['profile']->value->status;?>
" name="user-status-update" id="user-status-update"
                   maxlength="<?php echo $_smarty_tpl->tpl_vars['settings']->value->status_max_length;?>
"
                   placeholder="<?php echo smarty_function_text(array('text'=>"profile_update_status_placeholder"),$_smarty_tpl);?>
"/>
            <div class="update-status-actions">
                <i class="factory-icon icon-loader user_status_loader" style="display: none;"></i>
                <input type="button" value="<?php echo smarty_function_jtext(array('_'=>'profile_user_status_submit_status'),$_smarty_tpl);?>
"
                       class="update-status btn btn-small btn-primary">
            </div>
        <?php }else{ ?>
            <?php echo $_smarty_tpl->tpl_vars['profile']->value->status;?>

        <?php }?>
    </div>
<?php }?>
<?php }} ?><?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 13:06:00
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\profile\tmpl\default_interact.tpl" */ ?>
<?php if ($_valid && !is_callable('content_58f1fe989aea55_24139309')) {function content_58f1fe989aea55_24139309($_smarty_tpl) {?><?php if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
if (!is_callable('smarty_function_jroute')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jroute.php';
if (!is_callable('smarty_function_jhtml')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jhtml.php';
?><div class="well well-small">
    <h3>
        <?php if ($_smarty_tpl->tpl_vars['profile']->value->isMyProfile()){?>
            <?php echo smarty_function_jtext(array('_'=>'profile_fieldset_profile_actions'),$_smarty_tpl);?>

        <?php }else{ ?>
            <?php echo smarty_function_jtext(array('_'=>'profile_fieldset_interact'),$_smarty_tpl);?>

        <?php }?>
    </h3>

    <?php if ($_smarty_tpl->tpl_vars['profile']->value->isMyProfile()){?>
        <ul>
            <li><a href="<?php echo smarty_function_jroute(array('view'=>'edit'),$_smarty_tpl);?>
"><i
                            class="factory-icon icon-user-pencil"></i><?php echo smarty_function_jtext(array('_'=>'profile_edit_profile'),$_smarty_tpl);?>
</a></li>
            <li><a href="<?php echo smarty_function_jroute(array('view'=>'settings'),$_smarty_tpl);?>
"><i
                            class="factory-icon icon-gear"></i><?php echo smarty_function_jtext(array('_'=>'profile_edit_settings'),$_smarty_tpl);?>
</a></li>
            <li><a href="<?php echo smarty_function_jroute(array('view'=>'mymembership'),$_smarty_tpl);?>
"><i
                            class="factory-icon icon-medal"></i><?php echo smarty_function_jtext(array('_'=>'profile_my_membership'),$_smarty_tpl);?>
</a></li>
        </ul>
    <?php }else{ ?>
        <?php if (0==$_smarty_tpl->tpl_vars['visitor']->value->status){?>
            <?php /*  Call merged included template "default_login.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default_login.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '67758f1fe988bfd03-05663689');
content_58f1fe989e5361_49641659($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default_login.tpl" */?>
        <?php }elseif(1==$_smarty_tpl->tpl_vars['visitor']->value->status){?>
            <?php /*  Call merged included template "default_fillin.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default_fillin.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '67758f1fe988bfd03-05663689');
content_58f1fe98a06ad4_64423796($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default_fillin.tpl" */?>
        <?php }else{ ?>
            <ul>
                <!-- Send a quick message -->
                <li><?php echo smarty_function_jhtml(array('_'=>'LoveFactory.QuickMessage','userId'=>$_smarty_tpl->tpl_vars['profile']->value->user_id),$_smarty_tpl);?>
</li>

                <!-- Friendship button -->
                <li><?php echo smarty_function_jhtml(array('_'=>'LoveFactory.FriendshipButton','userId'=>$_smarty_tpl->tpl_vars['profile']->value->user_id),$_smarty_tpl);?>
</li>

                <!-- Relationship button -->
                <li><?php echo smarty_function_jhtml(array('_'=>'LoveFactory.RelationshipButton','userId'=>$_smarty_tpl->tpl_vars['profile']->value->user_id),$_smarty_tpl);?>
</li>

                <!-- Write a message -->
                <?php if (($_smarty_tpl->tpl_vars['settings']->value->enable_messages)){?>
                    <li><a href="<?php echo smarty_function_jroute(array('view'=>('compose&receiver=').($_smarty_tpl->tpl_vars['profile']->value->user_id)),$_smarty_tpl);?>
"><i
                                    class="factory-icon icon-mail-pencil"></i><?php echo smarty_function_jtext(array('_'=>'profile_interact_write_message'),$_smarty_tpl);?>

                        </a></li>
                <?php }?>

                <!-- Send Interaction Wink -->
                <li><?php echo smarty_function_jhtml(array('_'=>'LoveFactory.InteractionButton','userId'=>$_smarty_tpl->tpl_vars['profile']->value->user_id,'interaction'=>'wink'),$_smarty_tpl);?>
</li>

                <!-- Send Interaction Kiss -->
                <li><?php echo smarty_function_jhtml(array('_'=>'LoveFactory.InteractionButton','userId'=>$_smarty_tpl->tpl_vars['profile']->value->user_id,'interaction'=>'kiss'),$_smarty_tpl);?>
</li>

                <!-- Send Interaction Hug -->
                <li><?php echo smarty_function_jhtml(array('_'=>'LoveFactory.InteractionButton','userId'=>$_smarty_tpl->tpl_vars['profile']->value->user_id,'interaction'=>'hug'),$_smarty_tpl);?>
</li>

                <li>
                    <i class="factory-icon icon-image"></i><a
                            href="<?php echo smarty_function_jroute(array('view'=>("photos&user_id=").($_smarty_tpl->tpl_vars['profile']->value->user_id)),$_smarty_tpl);?>
"><?php echo smarty_function_jtext(array('_'=>"profile_interact_gallery"),$_smarty_tpl);?>
</a>
                </li>

                <!-- Block -->
                <li><?php echo smarty_function_jhtml(array('_'=>'LoveFactory.BlockButton','userId'=>$_smarty_tpl->tpl_vars['profile']->value->user_id,'isBlocked'=>$_smarty_tpl->tpl_vars['profile']->value->blocked),$_smarty_tpl);?>
</li>

                <!-- Report -->
                <li><?php echo smarty_function_jhtml(array('_'=>'LoveFactory.reportButton','type'=>'profile','id'=>$_smarty_tpl->tpl_vars['profile']->value->user_id,'reported'=>false),$_smarty_tpl);?>
</li>
            </ul>
        <?php }?>
    <?php }?>
</div>

<?php }} ?><?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 13:06:00
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\profile\tmpl\default_login.tpl" */ ?>
<?php if ($_valid && !is_callable('content_58f1fe989e5361_49641659')) {function content_58f1fe989e5361_49641659($_smarty_tpl) {?><?php if (!is_callable('smarty_function_jroute')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jroute.php';
if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
?><a href="<?php echo smarty_function_jroute(array('raw'=>('index.php?option=com_users&view=login&return=').($_smarty_tpl->tpl_vars['visitor']->value->referer)),$_smarty_tpl);?>
"><i
            class="factory-icon icon-lock"></i><?php echo smarty_function_jtext(array('_'=>'profile_login_to_interact'),$_smarty_tpl);?>
</a>
<?php }} ?><?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 13:06:00
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\profile\tmpl\default_fillin.tpl" */ ?>
<?php if ($_valid && !is_callable('content_58f1fe98a06ad4_64423796')) {function content_58f1fe98a06ad4_64423796($_smarty_tpl) {?><?php if (!is_callable('smarty_function_jroute')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jroute.php';
if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
?><a href="<?php echo smarty_function_jroute(array('view'=>'fillin'),$_smarty_tpl);?>
"><i class="factory-icon icon-user-plus"></i><?php echo smarty_function_jtext(array('_'=>'profile_fillin_to_interact'),$_smarty_tpl);?>
</a>
<?php }} ?><?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 13:06:00
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\profile\tmpl\default_ratings.tpl" */ ?>
<?php if ($_valid && !is_callable('content_58f1fe98a7d952_83324285')) {function content_58f1fe98a7d952_83324285($_smarty_tpl) {?><?php if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
if (!is_callable('smarty_function_jhtml')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jhtml.php';
if (!is_callable('smarty_function_jroute')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jroute.php';
?><div class="well well-small ratings">
    <h3><?php echo smarty_function_jtext(array('_'=>'profile_ratings_fieldset_title'),$_smarty_tpl);?>
</h3>

    <!-- Rate profile -->
    <?php if (!$_smarty_tpl->tpl_vars['profile']->value->isMyProfile()&&(!$_smarty_tpl->tpl_vars['ratings']->value->myRating||$_smarty_tpl->tpl_vars['ratings']->value->allowUpdate)){?>
        <div class="profile-vote">
            <?php if ((0==$_smarty_tpl->tpl_vars['visitor']->value->status)){?>
                <?php /*  Call merged included template "default_login.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default_login.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '67758f1fe988bfd03-05663689');
content_58f1fe989e5361_49641659($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default_login.tpl" */?>
            <?php }elseif(1==$_smarty_tpl->tpl_vars['visitor']->value->status){?>
                <?php /*  Call merged included template "default_fillin.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default_fillin.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '67758f1fe988bfd03-05663689');
content_58f1fe98a06ad4_64423796($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default_fillin.tpl" */?>
            <?php }else{ ?>
                <div><b><?php echo smarty_function_jtext(array('_'=>'profile_ratings_rate_profile'),$_smarty_tpl);?>
</b></div>
                <i class="factory-icon icon-loader stars-loader" style="display: none;"></i>
                <div class="rating-stars">
                    <?php echo smarty_function_jhtml(array('_'=>'LoveFactory.rating','userId'=>$_smarty_tpl->tpl_vars['profile']->value->user_id),$_smarty_tpl);?>

                </div>
            <?php }?>
        </div>
    <?php }?>

    <!-- Profile rating -->
    <div class="profile-rating">
        <b><?php echo $_smarty_tpl->tpl_vars['profile']->value->rating;?>
</b><br/><?php echo smarty_function_jtext(array('plural'=>'profile_ratings_current_votes','count'=>$_smarty_tpl->tpl_vars['profile']->value->votes),$_smarty_tpl);?>

    </div>

    <!-- My rating -->
    <?php if (!$_smarty_tpl->tpl_vars['profile']->value->isMyProfile()&&$_smarty_tpl->tpl_vars['ratings']->value->myRating){?>
        <div class="my-rating">
            <b><?php echo $_smarty_tpl->tpl_vars['ratings']->value->myRating;?>
</b><br/><?php echo smarty_function_jtext(array('_'=>'profile_ratings_my_rating'),$_smarty_tpl);?>

        </div>
    <?php }?>

    <!-- Latest votes -->
    <?php if ($_smarty_tpl->tpl_vars['ratings']->value->latestRatings){?>
        <div class="latest-votes">
            <div><b><?php echo smarty_function_jtext(array('_'=>'profile_ratings_latest_votes'),$_smarty_tpl);?>
</b></div>

            <ul>
                <?php  $_smarty_tpl->tpl_vars['rating'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rating']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ratings']->value->latestRatings; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['rating']->key => $_smarty_tpl->tpl_vars['rating']->value){
$_smarty_tpl->tpl_vars['rating']->_loop = true;
?>
                    <li>
                        <div class="vote"><i class="factory-icon icon-star"></i><?php echo $_smarty_tpl->tpl_vars['rating']->value->rating;?>
</div>
                        <?php if ($_smarty_tpl->tpl_vars['rating']->value->valid_user){?>
                            <a href="<?php echo smarty_function_jroute(array('view'=>('profile&user_id=').($_smarty_tpl->tpl_vars['rating']->value->sender_id)),$_smarty_tpl);?>
"><i
                                        class="factory-icon icon-user"></i><?php echo $_smarty_tpl->tpl_vars['rating']->value->display_name;?>
</a>
                        <?php }else{ ?>
                            <span><i class="factory-icon icon-user"></i><?php echo smarty_function_jtext(array('_'=>'profile_rating_user_removed'),$_smarty_tpl);?>
</span>
                        <?php }?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php }?>
</div>
<?php }} ?>