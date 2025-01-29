<?php
/* Smarty version 4.3.4, created on 2025-01-27 15:41:40
  from '/var/www/html/automotoboutic/admin919wlkwpjawfriiadmx/themes/default/template/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_67979b2493fc16_47958456',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '363ded2122e82ab2da19ff99ebda3032e9ed0ad6' => 
    array (
      0 => '/var/www/html/automotoboutic/admin919wlkwpjawfriiadmx/themes/default/template/footer.tpl',
      1 => 1718360660,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:error.tpl' => 1,
  ),
),false)) {
function content_67979b2493fc16_47958456 (Smarty_Internal_Template $_smarty_tpl) {
?>         <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayAdminEndContent'),$_smarty_tpl ) );?>

	</div>
</div>
<?php if ($_smarty_tpl->tpl_vars['display_footer']->value) {?>
<div id="footer" class="bootstrap">
	<div class="col-sm-12">
		<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>"displayBackOfficeFooter"),$_smarty_tpl ) );?>

	</div>
</div>
<?php }
if ((isset($_smarty_tpl->tpl_vars['php_errors']->value))) {?>
	<?php $_smarty_tpl->_subTemplateRender("file:error.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>

<?php if ((isset($_smarty_tpl->tpl_vars['modals']->value))) {?>
<div class="bootstrap">
	<?php echo $_smarty_tpl->tpl_vars['modals']->value;?>

</div>
<?php }?>

</body>
</html>
<?php }
}
