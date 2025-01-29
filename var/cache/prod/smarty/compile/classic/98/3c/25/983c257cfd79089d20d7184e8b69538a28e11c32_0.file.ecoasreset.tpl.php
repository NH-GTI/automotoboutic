<?php
/* Smarty version 4.3.4, created on 2025-01-28 09:01:35
  from '/var/www/html/automotoboutic/modules/ecordersandstock/views/templates/admin/ecoasreset.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_67988edf93da83_52855258',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '983c257cfd79089d20d7184e8b69538a28e11c32' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/ecordersandstock/views/templates/admin/ecoasreset.tpl',
      1 => 1731417328,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67988edf93da83_52855258 (Smarty_Internal_Template $_smarty_tpl) {
?><input type="hidden" name="ecoas_token" value="<?php echo $_smarty_tpl->tpl_vars['ecoas_token']->value;?>
" id="ecoas_token" />
<input type="hidden" name="ecoas_id_order" value="<?php echo $_smarty_tpl->tpl_vars['ecoas_id_order']->value;?>
" id="ecoas_id_order" />
<?php }
}
