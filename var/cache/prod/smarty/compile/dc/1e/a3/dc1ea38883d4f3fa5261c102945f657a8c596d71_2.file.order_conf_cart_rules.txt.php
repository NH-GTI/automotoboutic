<?php
/* Smarty version 4.3.4, created on 2025-01-28 10:57:54
  from '/var/www/html/automotoboutic/mails/_partials/order_conf_cart_rules.txt' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6798aa2225cc73_04973407',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dc1ea38883d4f3fa5261c102945f657a8c596d71' => 
    array (
      0 => '/var/www/html/automotoboutic/mails/_partials/order_conf_cart_rules.txt',
      1 => 1718360660,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6798aa2225cc73_04973407 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list']->value, 'cart_rule');
$_smarty_tpl->tpl_vars['cart_rule']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['cart_rule']->value) {
$_smarty_tpl->tpl_vars['cart_rule']->do_else = false;
?>
	<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['cart_rule']->value['voucher_name'], ENT_QUOTES, 'UTF-8');?>
  <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['cart_rule']->value['voucher_reduction'], ENT_QUOTES, 'UTF-8');?>

<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
