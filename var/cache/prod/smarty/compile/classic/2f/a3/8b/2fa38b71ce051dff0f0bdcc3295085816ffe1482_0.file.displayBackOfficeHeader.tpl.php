<?php
/* Smarty version 4.3.4, created on 2025-01-27 17:20:12
  from '/var/www/html/automotoboutic/modules/sellermania/views/templates/hook/displayBackOfficeHeader.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6797b23cc17ec3_32656685',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2fa38b71ce051dff0f0bdcc3295085816ffe1482' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/sellermania/views/templates/hook/displayBackOfficeHeader.tpl',
      1 => 1731498666,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6797b23cc17ec3_32656685 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
>
    var nb_sellermania_orders_in_error = <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'intval' ][ 0 ], array( $_smarty_tpl->tpl_vars['nb_orders_in_error']->value ));?>
;
    var txt_sellermania_orders_in_error = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sellermania orders could not be imported. Look at the module configuration for more details.','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
    var sellermania_invoice_url = '<?php echo $_smarty_tpl->tpl_vars['sellermania_invoice_url']->value;?>
';

    var sellermania_admin_orders_url = '<?php echo $_smarty_tpl->tpl_vars['sellermania_admin_orders_url']->value;?>
';
    var sellermania_default_carrier = '<?php echo $_smarty_tpl->tpl_vars['sellermania_default_carrier']->value;?>
';
    var txt_sellermania_confirm_orders = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Confirm selected Sellermania orders','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
    var txt_sellermania_send_orders = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Set selected Sellermania orders as sent','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
    var txt_sellermania_select_at_least_one_order = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'You have to select at least one order','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
    var txt_sellermania_error_occured = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'An error occured','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
    var txt_sellermania_carrier_selection = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Which carrier do you want to use?','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
    var txt_sellermania_orders_updated = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Orders were successfully updated','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
    var txt_sellermania_select_all = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Select all orders','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
    var txt_sellermania_unselect_all = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Unselect all orders','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
    var txt_sellermania_timeout_exception = "<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sellermania rejected the request (too many requests has been made), please wait a few seconds and try again.','mod'=>'sellermania'),$_smarty_tpl ) );?>
";
<?php echo '</script'; ?>
>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value == '17') {?>
    <?php $_smarty_tpl->_assignInScope('ps_version', "16");
}
echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['sellermania_module_path']->value;?>
views/js/displayBackOfficeHeader.js"><?php echo '</script'; ?>
>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value >= '17') {?>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['sellermania_module_path']->value;?>
views/js/displayBackOfficeHeader-16.js"><?php echo '</script'; ?>
>
<?php } else { ?>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['sellermania_module_path']->value;?>
views/js/displayBackOfficeHeader-<?php echo $_smarty_tpl->tpl_vars['ps_version']->value;?>
.js"><?php echo '</script'; ?>
>
<?php }?>

<?php }
}
