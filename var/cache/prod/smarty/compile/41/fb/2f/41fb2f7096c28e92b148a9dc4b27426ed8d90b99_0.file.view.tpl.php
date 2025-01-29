<?php
/* Smarty version 4.3.4, created on 2025-01-27 15:41:40
  from '/var/www/html/automotoboutic/modules/storecommander/views/templates/admin/store_commander/helpers/view/view.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_67979b2483c858_04135791',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '41fb2f7096c28e92b148a9dc4b27426ed8d90b99' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/storecommander/views/templates/admin/store_commander/helpers/view/view.tpl',
      1 => 1674209578,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67979b2483c858_04135791 (Smarty_Internal_Template $_smarty_tpl) {
echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['html']->value,'htmlall','UTF-8' ));?>

<?php if (!empty($_smarty_tpl->tpl_vars['sc_url']->value)) {?>
<fieldset><legend>Store Commander</legend>
    <label><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['sc_title']->value,'htmlall','UTF-8' ));?>
</label>
    <div class="margin-form">
        <?php echo '<script'; ?>
>
            document.location="<?php echo $_smarty_tpl->tpl_vars['sc_url']->value;?>
";
        <?php echo '</script'; ?>
>
    </div>
</fieldset>
<?php }
}
}
