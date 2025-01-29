<?php
/* Smarty version 4.3.4, created on 2025-01-29 12:07:00
  from '/var/www/html/automotoboutic/modules/dpdfrance/views/templates/front/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_679a0bd44265a9_81629968',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0ac43b04a8e25ec838ae40092901c3cccd10044d' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/dpdfrance/views/templates/front/header.tpl',
      1 => 1730819392,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_679a0bd44265a9_81629968 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
    var dpdfranceRelaisCarrierId  = "<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['dpdfrance_relais_carrier_id']->value,'javascript','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
";
    var dpdfrancePredictCarrierId = "<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['dpdfrance_predict_carrier_id']->value,'javascript','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
";
    var dpdfrance_cart_id         = "<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['dpdfrance_cart']->value->id,'javascript','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
";
    var dpdfrance_base_dir        = "<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['dpdfrance_base_dir']->value,'javascript','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
";
<?php echo '</script'; ?>
>
<?php }
}
