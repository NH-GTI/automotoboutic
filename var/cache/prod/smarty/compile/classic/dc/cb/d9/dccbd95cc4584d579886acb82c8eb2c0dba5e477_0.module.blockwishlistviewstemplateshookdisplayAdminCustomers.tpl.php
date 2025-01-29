<?php
/* Smarty version 4.3.4, created on 2025-01-28 09:20:59
  from 'module:blockwishlistviewstemplateshookdisplayAdminCustomers.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6798936b1af4f2_80908687',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dccbd95cc4584d579886acb82c8eb2c0dba5e477' => 
    array (
      0 => 'module:blockwishlistviewstemplateshookdisplayAdminCustomers.tpl',
      1 => 1691748513,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6798936b1af4f2_80908687 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="col">
  <div class="card">
    <h3 class="card-header">
      <i class="material-icons">remove_red_eye</i>
      <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blockwishlist']->value,'html','UTF-8' ));?>

      <span class="badge badge-primary rounded">0</span>
    </h3>
    <div class="card-body">
      <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['blockwishlist']->value,'html','UTF-8' ));?>

    </div>
  </div>
</div>
<?php }
}
