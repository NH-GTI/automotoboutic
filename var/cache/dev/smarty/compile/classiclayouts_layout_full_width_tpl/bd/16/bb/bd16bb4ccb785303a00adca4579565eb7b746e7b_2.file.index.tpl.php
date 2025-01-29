<?php
/* Smarty version 4.3.4, created on 2025-01-29 14:44:24
  from '/var/www/html/automotoboutic/themes/classic/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_679a30b82e8f07_09828346',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bd16bb4ccb785303a00adca4579565eb7b746e7b' => 
    array (
      0 => '/var/www/html/automotoboutic/themes/classic/templates/index.tpl',
      1 => 1738157878,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_679a30b82e8f07_09828346 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2049911299679a30b82e5218_70750709', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_1349217337679a30b82e5b54_92378763 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_270891783679a30b82e70e4_96547800 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_82518044679a30b82e69e4_93497646 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_270891783679a30b82e70e4_96547800', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_2049911299679a30b82e5218_70750709 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_2049911299679a30b82e5218_70750709',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_1349217337679a30b82e5b54_92378763',
  ),
  'page_content' => 
  array (
    0 => 'Block_82518044679a30b82e69e4_93497646',
  ),
  'hook_home' => 
  array (
    0 => 'Block_270891783679a30b82e70e4_96547800',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1349217337679a30b82e5b54_92378763', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_82518044679a30b82e69e4_93497646', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
