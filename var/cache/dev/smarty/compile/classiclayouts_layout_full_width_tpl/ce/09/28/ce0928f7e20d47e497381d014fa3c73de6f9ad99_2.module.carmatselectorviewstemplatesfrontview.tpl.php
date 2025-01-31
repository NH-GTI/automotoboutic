<?php
/* Smarty version 4.3.4, created on 2025-01-29 14:41:49
  from 'module:carmatselectorviewstemplatesfrontview.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_679a301d31ee84_41322802',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ce0928f7e20d47e497381d014fa3c73de6f9ad99' => 
    array (
      0 => 'module:carmatselectorviewstemplatesfrontview.tpl',
      1 => 1738157877,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_679a301d31ee84_41322802 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>
<!-- begin /var/www/html/automotoboutic/modules/carmatselector/views/templates/front/view.tpl -->

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_787772848679a301d31ac51_69009234', 'page_content');
?>
<!-- end /var/www/html/automotoboutic/modules/carmatselector/views/templates/front/view.tpl --><?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content'} */
class Block_787772848679a301d31ac51_69009234 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content' => 
  array (
    0 => 'Block_787772848679a301d31ac51_69009234',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <!-- Add Tailwind CSS -->
    <?php echo '<script'; ?>
 src="https://cdn.tailwindcss.com"><?php echo '</script'; ?>
>

    <!-- Pass PHP data to JavaScript -->
    <?php echo '<script'; ?>
>
        window.CARMAT_INITIAL_DATA = <?php echo $_smarty_tpl->tpl_vars['carmatData']->value;?>
;
        window.CARMAT_AJAX_URL = '<?php echo $_smarty_tpl->tpl_vars['ajaxUrl']->value;?>
';
        window.CARMAT_TOKEN = '<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
';
        console.log('Initial data:', window.CARMAT_INITIAL_DATA);
    <?php echo '</script'; ?>
>

    <div id="carmat-app"></div>

    <!-- Load Vue app -->
    <?php echo '<script'; ?>
 src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['urls']->value['base_url'], ENT_QUOTES, 'UTF-8');?>
modules/carmatselector/views/js/app.js"><?php echo '</script'; ?>
>
<?php
}
}
/* {/block 'page_content'} */
}
