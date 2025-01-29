<?php
/* Smarty version 4.3.4, created on 2025-01-27 17:44:57
  from 'module:ps_emailalertsviewstemplatesfrontmailalertsaccount.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6797b809a153d4_14544892',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a70f1148674812fc2b12ef98cae998f807e5133a' => 
    array (
      0 => 'module:ps_emailalertsviewstemplatesfrontmailalertsaccount.tpl',
      1 => 1704885770,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
    'module:ps_emailalerts/views/templates/front/mailalerts-account-line.tpl' => 1,
  ),
),false)) {
function content_6797b809a153d4_14544892 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9571409846797b809a0b8a0_87127682', 'page_title');
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18975885586797b809a0e7e7_70551964', 'page_content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'customer/page.tpl');
}
/* {block 'page_title'} */
class Block_9571409846797b809a0b8a0_87127682 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_title' => 
  array (
    0 => 'Block_9571409846797b809a0b8a0_87127682',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'My alerts','d'=>'Modules.Emailalerts.Shop'),$_smarty_tpl ) );?>

<?php
}
}
/* {/block 'page_title'} */
/* {block 'page_content'} */
class Block_18975885586797b809a0e7e7_70551964 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content' => 
  array (
    0 => 'Block_18975885586797b809a0e7e7_70551964',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

  <?php if ($_smarty_tpl->tpl_vars['mailAlerts']->value) {?>
    <ul>
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mailAlerts']->value, 'mailAlert');
$_smarty_tpl->tpl_vars['mailAlert']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['mailAlert']->value) {
$_smarty_tpl->tpl_vars['mailAlert']->do_else = false;
?>
        <li class="p-1 m-1" style="display:flex;align-items:center;background:white"><?php $_smarty_tpl->_subTemplateRender('module:ps_emailalerts/views/templates/front/mailalerts-account-line.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('mailAlert'=>$_smarty_tpl->tpl_vars['mailAlert']->value), 0, true);
?></li>
      <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </ul>
  <?php } else { ?>
    <div class="alert alert-info" role="alert" data-alert="info"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No mail alerts yet.','d'=>'Modules.Emailalerts.Shop'),$_smarty_tpl ) );?>
</div>
  <?php }
}
}
/* {/block 'page_content'} */
}
