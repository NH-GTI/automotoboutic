<?php
/* Smarty version 4.3.4, created on 2025-01-29 13:20:26
  from '/var/www/html/automotoboutic/themes/classic/templates/checkout/_partials/cart-summary-subtotals.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_679a1d0ae7fbb8_82193551',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7c143bb14e5178a82577108250a30fdea7c66087' => 
    array (
      0 => '/var/www/html/automotoboutic/themes/classic/templates/checkout/_partials/cart-summary-subtotals.tpl',
      1 => 1708963242,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_679a1d0ae7fbb8_82193551 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="card-block cart-summary-subtotals-container js-cart-summary-subtotals-container">

  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['cart']->value['subtotals'], 'subtotal');
$_smarty_tpl->tpl_vars['subtotal']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['subtotal']->value) {
$_smarty_tpl->tpl_vars['subtotal']->do_else = false;
?>
    <?php if ($_smarty_tpl->tpl_vars['subtotal']->value && preg_match_all('/[^\s]/u',$_smarty_tpl->tpl_vars['subtotal']->value['value'], $tmp) > 0 && $_smarty_tpl->tpl_vars['subtotal']->value['type'] !== 'tax') {?>
      <div class="cart-summary-line cart-summary-subtotals" id="cart-subtotal-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['subtotal']->value['type'], ENT_QUOTES, 'UTF-8');?>
">

        <span class="label">
            <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['subtotal']->value['label'], ENT_QUOTES, 'UTF-8');?>

        </span>

        <span class="value">
          <?php if ('discount' == $_smarty_tpl->tpl_vars['subtotal']->value['type']) {?>-&nbsp;<?php }
echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['subtotal']->value['value'], ENT_QUOTES, 'UTF-8');?>

        </span>
      </div>
    <?php }?>
  <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

</div>

<?php }
}
