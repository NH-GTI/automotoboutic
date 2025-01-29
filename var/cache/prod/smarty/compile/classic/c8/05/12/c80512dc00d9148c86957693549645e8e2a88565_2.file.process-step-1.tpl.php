<?php
/* Smarty version 4.3.4, created on 2025-01-27 14:22:16
  from '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-1.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_6797888822f7d1_12385301',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c80512dc00d9148c86957693549645e8e2a88565' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-1.tpl',
      1 => 1736238850,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6797888822f7d1_12385301 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_192911425567978888210d10_77356197', 'process_step_1_content');
?>

<?php }
/* {block 'process_step_1_content'} */
class Block_192911425567978888210d10_77356197 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_1_content' => 
  array (
    0 => 'Block_192911425567978888210d10_77356197',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/automotoboutic/vendor/smarty/smarty/libs/plugins/function.math.php','function'=>'smarty_function_math',),));
?>

	<div class="setup-content block_custom" id="step-1">
		<div class="container">
			<form role="form" action="" method="post">
				<div>
				<h3 class="selector-msg-first-step" style="text-align: center;">Tapis sur mesure non échangeable et non remboursable</h3>
					<p class="custom-warning-message">Livraison des tapis sur mesure sous 8 à 12 jours ouvrés</p>     
					<p class="title_block" style="text-align: center;">
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sélectionnez la marque puis le modèle de votre véhicule','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>
					<div class="row">
						<div class="col-md-8 offset-md-2 block_form">
														<div class="form-group row ">
								<label
									class="col-md-5 form-control-label required"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Marque de votre véhicule :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</label>
								<div class="col-md-7">
									<select name="ma" class="form-control form-control-select"
										onchange="$(this).parents('form').eq(0).submit();">
										<option value=""><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sélectionnez la marque','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</option>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['featured_brands']->value, 'marque');
$_smarty_tpl->tpl_vars['marque']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['marque']->value) {
$_smarty_tpl->tpl_vars['marque']->do_else = false;
?>
											<option value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['marque']->value['id_feature_value'], ENT_QUOTES, 'UTF-8');?>
"
												<?php if ((isset($_smarty_tpl->tpl_vars['marque_selected']->value)) && $_smarty_tpl->tpl_vars['marque_selected']->value == $_smarty_tpl->tpl_vars['marque']->value['id_feature_value']) {?>selected="selected"
												<?php }?>>
												<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['marque']->value['value'], ENT_QUOTES, 'UTF-8');?>

											</option>
										<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										<optgroup label="---------">
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['marques']->value, 'marque');
$_smarty_tpl->tpl_vars['marque']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['marque']->value) {
$_smarty_tpl->tpl_vars['marque']->do_else = false;
?>
												<option value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['marque']->value['id_feature_value'], ENT_QUOTES, 'UTF-8');?>
"
													<?php if ((isset($_smarty_tpl->tpl_vars['marque_selected']->value)) && $_smarty_tpl->tpl_vars['marque_selected']->value == $_smarty_tpl->tpl_vars['marque']->value['id_feature_value']) {?>selected="selected"
													<?php }?>>
													<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['marque']->value['value'], ENT_QUOTES, 'UTF-8');?>

												</option>
											<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group row ">
								<label
									class="col-md-5 form-control-label required"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Modèle de votre véhicule :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</label>
								<div class="col-md-7">
									<select name="mo" class="form-control form-control-select" <?php if (empty($_smarty_tpl->tpl_vars['marque_selected']->value)) {?>
										disabled="disabled" <?php }?>>
										<option value=""><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sélectionnez le modèle','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</option>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['modeles']->value, 'modele');
$_smarty_tpl->tpl_vars['modele']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['modele']->value) {
$_smarty_tpl->tpl_vars['modele']->do_else = false;
?>
											<option value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['modele']->value['id_feature_value'], ENT_QUOTES, 'UTF-8');?>
"
												<?php if ((isset($_smarty_tpl->tpl_vars['modele_selected']->value)) && $_smarty_tpl->tpl_vars['modele_selected']->value == $_smarty_tpl->tpl_vars['modele']->value['id_feature_value']) {?>selected="selected"
												<?php }?>>
												<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['modele']->value['value'], ENT_QUOTES, 'UTF-8');?>

											</option>
										<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</select>
								</div>
							</div>
							<button class="btn btn-primary btn-lg pull-right nextBtnstep" type="submit">
								<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Etape suivante','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

							</button>
						</div>
											</div>
			</form>
			<div style="margin-top:3em;border-top:1px solid #E5E5E5;display:none;" class="TEST-AVIS">
				<iframe
					src="https://cl.avis-verifies.com/fr/cache/9/0/e/90e983f7-d843-9514-89df-3656d7596ba2/widget4/90e983f7-d843-9514-89df-3656d7596ba2produit_all_index.html"
					style="border:none;width: 100%;margin:50px 0"></iframe>
			</div>
		</div>

		<div class="block_manufacturer_SM block_custom">
			<div class="container">
				<?php if ($_smarty_tpl->tpl_vars['marque_selected']->value > 0) {?>
					<p class="title_block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Tous les modèles','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['marque_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>
</p>
					<?php $_smarty_tpl->_assignInScope('index', 0);?>
					<div class="row">
						<div class="block_col_manu col-sm-4 col-md-3 same-height" data-same-height-group="list-model">
							<ul>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['modeles']->value, 'modele');
$_smarty_tpl->tpl_vars['modele']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['modele']->value) {
$_smarty_tpl->tpl_vars['modele']->do_else = false;
?>
																		<li><a
											href="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['link']->value->getModuleLink('lm_surmesure','surmesure',array('ma'=>$_smarty_tpl->tpl_vars['marque_selected']->value,'mo'=>$_smarty_tpl->tpl_vars['modele']->value['id_feature_value'])), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['modele']->value['value'], ENT_QUOTES, 'UTF-8');?>
</a>
									</li>
									<?php echo smarty_function_math(array('assign'=>'index','equation'=>'x + 1','x'=>$_smarty_tpl->tpl_vars['index']->value),$_smarty_tpl);?>

									<?php if ($_smarty_tpl->tpl_vars['index']->value%5 == 0 && count($_smarty_tpl->tpl_vars['modeles']->value) >= $_smarty_tpl->tpl_vars['index']->value) {?>
									</ul>
								</div>
								<div class="block_col_manu col-sm-4 col-md-3 same-height" data-same-height-group="list-model">
									<ul>
									<?php }?>
								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</ul>
						</div>
					</div>
				<?php } else { ?>
					<p class="title_block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Marques','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>
					<?php $_smarty_tpl->_assignInScope('index', 0);?>
					<div class="row">
						<div class="block_col_manu col-sm-4 col-md-3 same-height" data-same-height-group="list-model">
							<ul>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['marques']->value, 'marque');
$_smarty_tpl->tpl_vars['marque']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['marque']->value) {
$_smarty_tpl->tpl_vars['marque']->do_else = false;
?>
																		<li><a
											href="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['link']->value->getModuleLink('lm_surmesure','surmesure',array('ma'=>$_smarty_tpl->tpl_vars['marque']->value['id_feature_value'])), ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['marque']->value['value'], ENT_QUOTES, 'UTF-8');?>
</a>
									</li>
									<?php echo smarty_function_math(array('assign'=>'index','equation'=>'x + 1','x'=>$_smarty_tpl->tpl_vars['index']->value),$_smarty_tpl);?>

									<?php if ($_smarty_tpl->tpl_vars['index']->value%5 == 0 && count($_smarty_tpl->tpl_vars['marques']->value) >= $_smarty_tpl->tpl_vars['index']->value) {?>
									</ul>
								</div>
								<div class="block_col_manu col-sm-4 col-md-3 same-height" data-same-height-group="list-model">
									<ul>
									<?php }?>
								<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</ul>
						</div>
					</div>
				<?php }?>


			</div>
		</div>
		
	</div><!-- end step-1 -->
<?php
}
}
/* {/block 'process_step_1_content'} */
}
