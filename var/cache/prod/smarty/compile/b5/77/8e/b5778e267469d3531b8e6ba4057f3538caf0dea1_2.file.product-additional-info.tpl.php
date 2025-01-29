<?php
/* Smarty version 4.3.4, created on 2025-01-29 14:17:52
  from '/var/www/html/automotoboutic/themes/classic/templates/catalog/_partials/product-additional-info.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_679a2a80117312_01934536',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b5778e267469d3531b8e6ba4057f3538caf0dea1' => 
    array (
      0 => '/var/www/html/automotoboutic/themes/classic/templates/catalog/_partials/product-additional-info.tpl',
      1 => 1708963242,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_679a2a80117312_01934536 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="product-additional-info js-product-additional-info">
  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductAdditionalInfo','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

</div>
<?php }
}
