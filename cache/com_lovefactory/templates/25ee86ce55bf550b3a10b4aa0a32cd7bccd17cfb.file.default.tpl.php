<?php /* Smarty version Smarty-3.1.12, created on 2017-04-15 12:46:23
         compiled from "C:\xampp\htdocs\Joomla\components\com_lovefactory\views\memberships\tmpl\default.tpl" */ ?>
<?php /*%%SmartyHeaderCode:83858f1f9ff096492-87827069%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '25ee86ce55bf550b3a10b4aa0a32cd7bccd17cfb' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Joomla\\components\\com_lovefactory\\views\\memberships\\tmpl\\default.tpl',
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
  'nocache_hash' => '83858f1f9ff096492-87827069',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'viewName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_58f1f9ff27b529_18620503',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58f1f9ff27b529_18620503')) {function content_58f1f9ff27b529_18620503($_smarty_tpl) {?><?php if (!is_callable('smarty_function_text')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/ThePhpFactory/LoveFactory/Smarty/Plugins\\function.text.php';
if (!is_callable('smarty_function_cycle')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.cycle.php';
if (!is_callable('smarty_function_jtext')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jtext.php';
if (!is_callable('smarty_function_jroute')) include 'C:\\xampp\\htdocs\\Joomla/components/com_lovefactory/lib/Smarty/plugins\\function.jroute.php';
?><div class="lovefactory-view view-<?php echo $_smarty_tpl->tpl_vars['viewName']->value;?>
">
    

    <h1 class="heading"><?php echo smarty_function_text(array('text'=>("page_heading_title_").($_smarty_tpl->tpl_vars['viewName']->value)),$_smarty_tpl);?>
</h1>

    
    <table class="memberships">
        <thead>
        <tr>
            <td></td>
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                <th><?php echo $_smarty_tpl->tpl_vars['item']->value->title;?>
</th>
            <?php } ?>
        </tr>
        </thead>

        <tbody>
        <?php  $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['key']->_loop = false;
 $_smarty_tpl->tpl_vars['feature'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['features']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['key']->key => $_smarty_tpl->tpl_vars['key']->value){
$_smarty_tpl->tpl_vars['key']->_loop = true;
 $_smarty_tpl->tpl_vars['feature']->value = $_smarty_tpl->tpl_vars['key']->key;
?>
            <tr class="<?php echo smarty_function_cycle(array('values'=>",alternate"),$_smarty_tpl);?>
">
                <th>
                    <i class="factory-icon icon-membership-feature-<?php echo $_smarty_tpl->tpl_vars['feature']->value;?>
"></i>
                    <?php echo smarty_function_jtext(array('_'=>("memberships_membership_features_").($_smarty_tpl->tpl_vars['feature']->value)),$_smarty_tpl);?>

                </th>
                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                    <td>
                        <?php if (isset($_smarty_tpl->tpl_vars['item']->value->features[$_smarty_tpl->tpl_vars['feature']->value])){?>
                            <?php echo $_smarty_tpl->tpl_vars['item']->value->features[$_smarty_tpl->tpl_vars['feature']->value];?>

                        <?php }?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <h1><?php echo smarty_function_jtext(array('_'=>'memberships_prices_title'),$_smarty_tpl);?>
</h1>
    <table class="memberships prices">
        <thead>
        <tr>
            <td></td>
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                <th><?php echo $_smarty_tpl->tpl_vars['item']->value->title;?>
</th>
            <?php } ?>
        </tr>
        </thead>

        <tbody>
        <?php  $_smarty_tpl->tpl_vars['pricing'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pricing']->_loop = false;
 $_smarty_tpl->tpl_vars['months'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['prices']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pricing']->key => $_smarty_tpl->tpl_vars['pricing']->value){
$_smarty_tpl->tpl_vars['pricing']->_loop = true;
 $_smarty_tpl->tpl_vars['months']->value = $_smarty_tpl->tpl_vars['pricing']->key;
?>
            <tr class="<?php echo smarty_function_cycle(array('values'=>",alternate"),$_smarty_tpl);?>
">
                <th><?php echo smarty_function_jtext(array('plural'=>'memberships_price_months','count'=>$_smarty_tpl->tpl_vars['months']->value),$_smarty_tpl);?>
</th>

                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                    <td class="<?php echo isset($_smarty_tpl->tpl_vars['pricing']->value[$_smarty_tpl->tpl_vars['item']->value->id]) ? 'right' : '';?>
">
                        <?php if (isset($_smarty_tpl->tpl_vars['pricing']->value[$_smarty_tpl->tpl_vars['item']->value->id])){?>
                            <?php if (!is_array($_smarty_tpl->tpl_vars['pricing']->value[$_smarty_tpl->tpl_vars['item']->value->id])){?>

                                <?php if ('0.00'==$_smarty_tpl->tpl_vars['pricing']->value[$_smarty_tpl->tpl_vars['item']->value->id]->amount){?>
                                    <a href="<?php echo smarty_function_jroute(array('task'=>('membership.free&id=').($_smarty_tpl->tpl_vars['pricing']->value[$_smarty_tpl->tpl_vars['item']->value->id]->id)),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['pricing']->value[$_smarty_tpl->tpl_vars['item']->value->id]->price;?>
</a>
                                <?php }else{ ?>
                                    <a href="<?php echo smarty_function_jroute(array('view'=>('membershipbuy&id=').($_smarty_tpl->tpl_vars['pricing']->value[$_smarty_tpl->tpl_vars['item']->value->id]->id)),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['pricing']->value[$_smarty_tpl->tpl_vars['item']->value->id]->price;?>
</a>
                                <?php }?>

                            <?php }else{ ?>

                                <?php  $_smarty_tpl->tpl_vars['prices'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['prices']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pricing']->value[$_smarty_tpl->tpl_vars['item']->value->id]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['prices']->key => $_smarty_tpl->tpl_vars['prices']->value){
$_smarty_tpl->tpl_vars['prices']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['prices']->key;
?>
                                    <?php  $_smarty_tpl->tpl_vars['price'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['price']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['prices']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['price']->key => $_smarty_tpl->tpl_vars['price']->value){
$_smarty_tpl->tpl_vars['price']->_loop = true;
?>
                                        <?php if ('0.00'==$_smarty_tpl->tpl_vars['price']->value['price']){?>
                                            <a class="gender"
                                               href="<?php echo smarty_function_jroute(array('task'=>('membership.free&id=').($_smarty_tpl->tpl_vars['id']->value)),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['price']->value['label'];?>
</a>
                                        <?php }else{ ?>
                                            <a class="gender"
                                               href="<?php echo smarty_function_jroute(array('view'=>('membershipbuy&id=').($_smarty_tpl->tpl_vars['id']->value)),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['price']->value['label'];?>
</a>
                                        <?php }?>
                                    <?php } ?>
                                <?php } ?>

                            <?php }?>
                        <?php }?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php if ($_smarty_tpl->tpl_vars['trials']->value){?>
        <h1><?php echo smarty_function_jtext(array('_'=>'memberships_trials_title'),$_smarty_tpl);?>
</h1>
        <table class="memberships prices">
            <thead>
            <tr>
                <td></td>
                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                    <th><?php echo $_smarty_tpl->tpl_vars['item']->value->title;?>
</th>
                <?php } ?>
            </tr>
            </thead>

            <tbody>
            <?php  $_smarty_tpl->tpl_vars['trial'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['trial']->_loop = false;
 $_smarty_tpl->tpl_vars['hours'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['trials']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['trial']->key => $_smarty_tpl->tpl_vars['trial']->value){
$_smarty_tpl->tpl_vars['trial']->_loop = true;
 $_smarty_tpl->tpl_vars['hours']->value = $_smarty_tpl->tpl_vars['trial']->key;
?>
                <tr class="<?php echo smarty_function_cycle(array('values'=>",alternate"),$_smarty_tpl);?>
">
                    <th><?php echo smarty_function_jtext(array('plural'=>'memberships_trials_hours','count'=>$_smarty_tpl->tpl_vars['hours']->value),$_smarty_tpl);?>
</th>

                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                        <td>
                            <?php if (isset($_smarty_tpl->tpl_vars['trial']->value[$_smarty_tpl->tpl_vars['item']->value->id])){?>
                                <a href="<?php echo smarty_function_jroute(array('task'=>('membership.trial&id=').($_smarty_tpl->tpl_vars['trial']->value[$_smarty_tpl->tpl_vars['item']->value->id]->id)),$_smarty_tpl);?>
"><?php echo smarty_function_jtext(array('_'=>'memberships_trials_free_trial'),$_smarty_tpl);?>
</a>
                            <?php }?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php }?>

</div>
<?php }} ?>