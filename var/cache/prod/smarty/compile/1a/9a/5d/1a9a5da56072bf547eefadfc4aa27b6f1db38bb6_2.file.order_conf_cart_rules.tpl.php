<?php
/* Smarty version 4.3.4, created on 2025-01-28 10:57:54
  from '/var/www/html/automotoboutic/mails/_partials/order_conf_cart_rules.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6798aa222612c0_18684728',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1a9a5da56072bf547eefadfc4aa27b6f1db38bb6' => 
    array (
      0 => '/var/www/html/automotoboutic/mails/_partials/order_conf_cart_rules.tpl',
      1 => 1718360660,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6798aa222612c0_18684728 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list']->value, 'cart_rule');
$_smarty_tpl->tpl_vars['cart_rule']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cart_rule']->value) {
$_smarty_tpl->tpl_vars['cart_rule']->do_else = false;
?>
	<tr class="conf_body">
		<td bgcolor="#f8f8f8" colspan="4" style="border:1px solid #D6D4D4;color:#333;padding:7px 0">
			<table class="table" style="width:100%;border-collapse:collapse">
				<tr>
					<td width="5" style="color:#333;padding:0"></td>
					<td align="right" style="color:#333;padding:0">
						<font size="2" face="Open-sans, sans-serif" color="#555454">
							<strong><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['cart_rule']->value['voucher_name'], ENT_QUOTES, 'UTF-8');?>
</strong>
						</font>
					</td>
					<td width="5" style="color:#333;padding:0"></td>
				</tr>
			</table>
		</td>
		<td bgcolor="#f8f8f8" colspan="4" style="border:1px solid #D6D4D4;color:#333;padding:7px 0">
			<table class="table" style="width:100%;border-collapse:collapse">
				<tr>
					<td width="5" style="color:#333;padding:0"></td>
					<td align="right" style="color:#333;padding:0">
						<font size="2" face="Open-sans, sans-serif" color="#555454">
							<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['cart_rule']->value['voucher_reduction'], ENT_QUOTES, 'UTF-8');?>

						</font>
					</td>
					<td width="5" style="color:#333;padding:0"></td>
				</tr>
			</table>
		</td>
	</tr>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
