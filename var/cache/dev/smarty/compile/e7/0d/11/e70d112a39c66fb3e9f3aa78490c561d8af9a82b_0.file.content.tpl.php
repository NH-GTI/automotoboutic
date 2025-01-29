<?php
/* Smarty version 4.3.4, created on 2025-01-29 12:27:45
  from '/var/www/html/automotoboutic/admin919wlkwpjawfriiadmx/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_679a10b1092966_93668857',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e70d112a39c66fb3e9f3aa78490c561d8af9a82b' => 
    array (
      0 => '/var/www/html/automotoboutic/admin919wlkwpjawfriiadmx/themes/default/template/content.tpl',
      1 => 1718360660,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_679a10b1092966_93668857 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>
<div id="content-message-box"></div>

<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
	<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

<?php }
}
}
