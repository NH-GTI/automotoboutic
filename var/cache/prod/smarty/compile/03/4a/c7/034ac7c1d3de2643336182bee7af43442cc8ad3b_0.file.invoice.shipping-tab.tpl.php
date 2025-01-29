<?php
/* Smarty version 4.3.4, created on 2025-01-27 17:42:53
  from '/var/www/html/automotoboutic/pdf/invoice.shipping-tab.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6797b78dc233f5_89743526',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '034ac7c1d3de2643336182bee7af43442cc8ad3b' => 
    array (
      0 => '/var/www/html/automotoboutic/pdf/invoice.shipping-tab.tpl',
      1 => 1718360660,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6797b78dc233f5_89743526 (Smarty_Internal_Template $_smarty_tpl) {
?><table id="shipping-tab" width="100%">
	<tr>
		<td class="shipping center small grey bold" width="44%"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Carrier','d'=>'Shop.Pdf','pdf'=>'true'),$_smarty_tpl ) );?>
</td>
		<td class="shipping center small white" width="56%"><?php echo $_smarty_tpl->tpl_vars['carrier']->value->name;?>
</td>
	</tr>
</table>
<?php }
}
