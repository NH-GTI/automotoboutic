<?php
/* Smarty version 4.3.4, created on 2025-01-29 00:04:31
  from 'module:productcommentsviewstemplateshookproductadditionalinfoquickview.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6799627f667c73_43319582',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'edcb0114684a4bb6be1ba88437b7bfde204517e6' => 
    array (
      0 => 'module:productcommentsviewstemplateshookproductadditionalinfoquickview.tpl',
      1 => 1713967824,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6799627f667c73_43319582 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['nb_comments']->value != 0) {?>
  <?php echo '<script'; ?>
 type="text/javascript">    
    $('#product-quickview-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['product']->value['id'], ENT_QUOTES, 'UTF-8');?>
').insertAfter($('.quickview #product-description-short'));
    $('#product-quickview-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['product']->value['id'], ENT_QUOTES, 'UTF-8');?>
 .grade-stars').rating({ grade: <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['average_grade']->value, ENT_QUOTES, 'UTF-8');?>
 });
    $('#product-quickview-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['product']->value['id'], ENT_QUOTES, 'UTF-8');?>
 .comments-nb').html('(<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['nb_comments']->value, ENT_QUOTES, 'UTF-8');?>
)');
  <?php echo '</script'; ?>
>

  <div id="product-quickview-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['product']->value['id'], ENT_QUOTES, 'UTF-8');?>
" class="product-quickview-review">
    <div class="grade-stars"></div>
    <div class="comments-nb"></div>
  </div>
</div>
<?php }
}
}
