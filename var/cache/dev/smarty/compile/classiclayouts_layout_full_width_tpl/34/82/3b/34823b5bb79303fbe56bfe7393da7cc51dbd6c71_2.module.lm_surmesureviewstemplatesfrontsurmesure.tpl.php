<?php
/* Smarty version 4.3.4, created on 2025-01-29 11:54:58
  from 'module:lm_surmesureviewstemplatesfrontsurmesure.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_679a09022a2172_26220948',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '34823b5bb79303fbe56bfe7393da7cc51dbd6c71' => 
    array (
      0 => 'module:lm_surmesureviewstemplatesfrontsurmesure.tpl',
      1 => 1732524662,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_679a09022a2172_26220948 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>
<!-- begin /var/www/html/automotoboutic/modules/lm_surmesure/views/templates/front/surmesure.tpl -->


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16110129679a090229fa20_20150647', 'page_content');
?>

<!-- end /var/www/html/automotoboutic/modules/lm_surmesure/views/templates/front/surmesure.tpl --><?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content'} */
class Block_16110129679a090229fa20_20150647 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content' => 
  array (
    0 => 'Block_16110129679a090229fa20_20150647',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['widget'][0], array( array('name'=>"lm_surmesure"),$_smarty_tpl ) );?>

<?php
}
}
/* {/block 'page_content'} */
}
