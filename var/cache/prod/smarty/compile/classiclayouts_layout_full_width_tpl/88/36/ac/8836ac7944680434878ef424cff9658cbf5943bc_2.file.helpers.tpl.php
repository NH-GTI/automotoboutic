<?php
/* Smarty version 4.3.4, created on 2025-01-29 10:38:04
  from '/var/www/html/automotoboutic/themes/classic/templates/_partials/helpers.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6799f6fc97d493_95276660',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8836ac7944680434878ef424cff9658cbf5943bc' => 
    array (
      0 => '/var/www/html/automotoboutic/themes/classic/templates/_partials/helpers.tpl',
      1 => 1708963242,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6799f6fc97d493_95276660 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->smarty->ext->_tplFunction->registerTplFunctions($_smarty_tpl, array (
  'renderLogo' => 
  array (
    'compiled_filepath' => '/var/www/html/automotoboutic/var/cache/prod/smarty/compile/classiclayouts_layout_full_width_tpl/88/36/ac/8836ac7944680434878ef424cff9658cbf5943bc_2.file.helpers.tpl.php',
    'uid' => '8836ac7944680434878ef424cff9658cbf5943bc',
    'call_name' => 'smarty_template_function_renderLogo_20594447136799f6fc978919_91640986',
  ),
));
?> 

<?php }
/* smarty_template_function_renderLogo_20594447136799f6fc978919_91640986 */
if (!function_exists('smarty_template_function_renderLogo_20594447136799f6fc978919_91640986')) {
function smarty_template_function_renderLogo_20594447136799f6fc978919_91640986(Smarty_Internal_Template $_smarty_tpl,$params) {
foreach ($params as $key => $value) {
$_smarty_tpl->tpl_vars[$key] = new Smarty_Variable($value, $_smarty_tpl->isRenderingCache);
}
?>

  <a href="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['urls']->value['pages']['index'], ENT_QUOTES, 'UTF-8');?>
">
    <img
      class="logo img-fluid"
      src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['shop']->value['logo_details']['src'], ENT_QUOTES, 'UTF-8');?>
"
      alt="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['shop']->value['name'], ENT_QUOTES, 'UTF-8');?>
"
      width="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['shop']->value['logo_details']['width'], ENT_QUOTES, 'UTF-8');?>
"
      height="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['shop']->value['logo_details']['height'], ENT_QUOTES, 'UTF-8');?>
">
  </a>
<?php
}}
/*/ smarty_template_function_renderLogo_20594447136799f6fc978919_91640986 */
}
