<?php
/* Smarty version 4.3.4, created on 2025-01-29 11:54:58
  from '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-2.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_679a090232a7e8_55071712',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8c2fe1ff8fba273cb5ebb16baf65f11ec2fc2949' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-2.tpl',
      1 => 1732524662,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_679a090232a7e8_55071712 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1919286842679a090231a7d4_86937807', 'process_step_2_content');
?>

<?php }
/* {block 'process_step_2_content'} */
class Block_1919286842679a090231a7d4_86937807 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_2_content' => 
  array (
    0 => 'Block_1919286842679a090231a7d4_86937807',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <?php echo '<script'; ?>
 type="text/javascript">
    var surmesure_img_url = "<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/";
	<?php echo '</script'; ?>
>
	
	<div class="setup-content block_custom block_model_step2" id="step-2">
		<div class="seotext"> <!-- .seotext-top -->
		</div>
		<div class="container">
			<p class="title_block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sélectionnez votre modèle dans la liste ci-dessous','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>
        </div> 
		<section class="cd-products-comparison-table">
			<div class="cd-products-table">
				<?php $_smarty_tpl->_assignInScope('counter', 0);?>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['modeles']->value, 'mod', false, 'key');
$_smarty_tpl->tpl_vars['mod']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['mod']->value) {
$_smarty_tpl->tpl_vars['mod']->do_else = false;
?>
					<form name="selectmod" method="post" action="">
						<h3><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['mod']->value['title'], ENT_QUOTES, 'UTF-8');?>
</h3>
						<p><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['mod']->value['caracs'], ENT_QUOTES, 'UTF-8');?>
</p>
						<input type="hidden" name="id_model" value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['mod']->value['id_feature_value'], ENT_QUOTES, 'UTF-8');?>
" />
						<input type="hidden" name="name_model" value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['mod']->value['value'], ENT_QUOTES, 'UTF-8');?>
" />
						<img class="custom_img_model" src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['mod']->value['img'], ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['mod']->value['value'], ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['mod']->value['img'] == "none") {?>
							style="display:none"
						<?php }?>>
						<input type="submit" value="Choisir" class="btn-primary nextBtnstep" />
					</form>
					<?php $_smarty_tpl->_assignInScope('counter', $_smarty_tpl->tpl_vars['counter']->value+1);?>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php
$_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['foo']->step = 1;$_smarty_tpl->tpl_vars['foo']->total = (int) ceil(($_smarty_tpl->tpl_vars['foo']->step > 0 ? 4-($_smarty_tpl->tpl_vars['counter']->value%4)+1 - (1) : 1-(4-($_smarty_tpl->tpl_vars['counter']->value%4))+1)/abs($_smarty_tpl->tpl_vars['foo']->step));
if ($_smarty_tpl->tpl_vars['foo']->total > 0) {
for ($_smarty_tpl->tpl_vars['foo']->value = 1, $_smarty_tpl->tpl_vars['foo']->iteration = 1;$_smarty_tpl->tpl_vars['foo']->iteration <= $_smarty_tpl->tpl_vars['foo']->total;$_smarty_tpl->tpl_vars['foo']->value += $_smarty_tpl->tpl_vars['foo']->step, $_smarty_tpl->tpl_vars['foo']->iteration++) {
$_smarty_tpl->tpl_vars['foo']->first = $_smarty_tpl->tpl_vars['foo']->iteration === 1;$_smarty_tpl->tpl_vars['foo']->last = $_smarty_tpl->tpl_vars['foo']->iteration === $_smarty_tpl->tpl_vars['foo']->total;?>
					<div></div>
				<?php }
}
?>
			</div> <!-- .cd-products-table -->
			<div class="seotext">
				<h2>Tapis <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'ucfirst' ][ 0 ], array( $_smarty_tpl->tpl_vars['marque_selected_nom']->value )), ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'ucfirst' ][ 0 ], array( $_smarty_tpl->tpl_vars['family_selected_nom']->value )), ENT_QUOTES, 'UTF-8');?>
 gammes</h2>
				<p>
					<?php echo $_smarty_tpl->tpl_vars['seoTexts']->value[1]['value'];?>

				</p>
			</div>
			<div class="seotext">
				<h2>Tapis <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'ucfirst' ][ 0 ], array( $_smarty_tpl->tpl_vars['marque_selected_nom']->value )), ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'ucfirst' ][ 0 ], array( $_smarty_tpl->tpl_vars['family_selected_nom']->value )), ENT_QUOTES, 'UTF-8');?>
 sur mesure</h2>
				<p>
					<?php echo $_smarty_tpl->tpl_vars['seoTexts']->value[2]['value'];?>

				</p>
			</div>
		</section> <!-- .cd-products-comparison-table -->
	</div><!-- end #step-2 -->
<?php
}
}
/* {/block 'process_step_2_content'} */
}
