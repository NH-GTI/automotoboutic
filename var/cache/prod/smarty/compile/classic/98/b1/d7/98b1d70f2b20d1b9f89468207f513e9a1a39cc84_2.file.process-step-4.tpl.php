<?php
/* Smarty version 4.3.4, created on 2025-01-27 14:46:48
  from '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-4.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_67978e48d59134_37467355',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '98b1d70f2b20d1b9f89468207f513e9a1a39cc84' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-4.tpl',
      1 => 1732524662,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67978e48d59134_37467355 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_80929632867978e48cfe027_71920535', 'process_step_4_content');
?>

<?php }
/* {block 'process_step_4_content'} */
class Block_80929632867978e48cfe027_71920535 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_4_content' => 
  array (
    0 => 'Block_80929632867978e48cfe027_71920535',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/automotoboutic/vendor/smarty/smarty/libs/plugins/modifier.replace.php','function'=>'smarty_modifier_replace',),1=>array('file'=>'/var/www/html/automotoboutic/vendor/smarty/smarty/libs/plugins/function.math.php','function'=>'smarty_function_math',),));
?>

	<div class="setup-content block_custom" id="step-4">
		<div class="container"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

			<p class="title_block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Choisissez votre configuration de tapis','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>
			<div class="block_choice_recap">
				<div class="row">
					<div class="col-md-8 form-choose-config-color">
						<div class="block_choice">
							<table class="table">
								<tbody>
									<?php $_smarty_tpl->_assignInScope('index', 0);?>
									<tr class="row">
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['configurations']->value, 'configuration', false, NULL, 'configurationsLoop', array (
));
$_smarty_tpl->tpl_vars['configuration']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['configuration']->value) {
$_smarty_tpl->tpl_vars['configuration']->do_else = false;
?>
											<td class="col-md-6">
												<label
													for="id_conf-id_product-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['id_conf'], ENT_QUOTES, 'UTF-8');?>
-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['product']->id, ENT_QUOTES, 'UTF-8');?>
"
													class="block_choice_container id_conf-id_product-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['id_conf'], ENT_QUOTES, 'UTF-8');?>
-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['product']->id, ENT_QUOTES, 'UTF-8');
if (!empty($_smarty_tpl->tpl_vars['configuration_selected']->value) && !empty($_smarty_tpl->tpl_vars['product_selected']->value) && $_smarty_tpl->tpl_vars['configuration_selected']->value == $_smarty_tpl->tpl_vars['configuration']->value['id_conf'] && $_smarty_tpl->tpl_vars['product_selected']->value == $_smarty_tpl->tpl_vars['configuration']->value['product']->id) {?> active<?php } elseif (empty($_smarty_tpl->tpl_vars['confProductChecked']->value)) {?> active<?php }?>">
													<div class="block_choice_top same-height"
														data-same-height-group="block_choice_top_data">
														<div class="row">
															<div class="choice_button col-xs-1 col-sm-1 col-md-1">
																<span class="custom-radio">
																	<input type="radio"
																		id="id_conf-id_product-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['id_conf'], ENT_QUOTES, 'UTF-8');?>
-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['product']->id, ENT_QUOTES, 'UTF-8');?>
"
																		name="id_conf-id_product"
																		value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['id_conf'], ENT_QUOTES, 'UTF-8');?>
-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['product']->id, ENT_QUOTES, 'UTF-8');?>
"
																		data-id_conf="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['id_conf'], ENT_QUOTES, 'UTF-8');?>
"
																		data-id_product="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['product']->id, ENT_QUOTES, 'UTF-8');?>
"
																		data-id_product="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['product']->id, ENT_QUOTES, 'UTF-8');?>
"
																		data-image="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/configurations/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['image'], ENT_QUOTES, 'UTF-8');?>
"
																		data-desc_target="#surmesure-conf_description-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['id_conf'], ENT_QUOTES, 'UTF-8');?>
-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['product']->id, ENT_QUOTES, 'UTF-8');?>
" <?php if (!empty($_smarty_tpl->tpl_vars['configuration_selected']->value) && !empty($_smarty_tpl->tpl_vars['product_selected']->value) && $_smarty_tpl->tpl_vars['configuration_selected']->value == $_smarty_tpl->tpl_vars['configuration']->value['id_conf'] && $_smarty_tpl->tpl_vars['product_selected']->value == $_smarty_tpl->tpl_vars['configuration']->value['product']->id) {?>checked="checked"
																		<?php $_smarty_tpl->_assignInScope('confProductChecked', true);?>
																	<?php } elseif (empty($_smarty_tpl->tpl_vars['confProductChecked']->value)) {?>checked="checked"
																	<?php $_smarty_tpl->_assignInScope('confProductChecked', true);?> <?php }?> />
																<span></span>
															</span>
														</div>
														<div class="col-xs-3 col-sm-3 col-md-3">
															<div class="img_choice">
																<img class="img-responsive"
																	src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/configurations/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['image'], ENT_QUOTES, 'UTF-8');?>
"
																	alt="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['value'], ENT_QUOTES, 'UTF-8');?>
" />
															</div>
														</div>
														<div class="col-xs-8 col-sm-8 col-md-8 desc_choice">
															<div id="surmesure-conf_description-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['id_conf'], ENT_QUOTES, 'UTF-8');?>
-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['configuration']->value['product']->id, ENT_QUOTES, 'UTF-8');?>
"
																class="details_choice_text">
																<?php echo $_smarty_tpl->tpl_vars['configuration']->value['description'];?>

															</div>
														</div>
													</div>
												</div>
												<div class="block_choice_price text-center">
													<div class="price_container"
														<?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_COOKIE['confidential_mode'],"html" )) == "ON") {?>
														style="display:none" <?php }?>>

														<?php if ($_smarty_tpl->tpl_vars['espace_pro']->value) {?>
															<?php $_smarty_tpl->_assignInScope('tax', 0);?>
														<?php } else { ?>
															<?php $_smarty_tpl->_assignInScope('tax', 1);?>
														<?php }?>
														<span
															class="price"><?php echo htmlspecialchars((string) smarty_modifier_replace(html_entity_decode($_smarty_tpl->tpl_vars['configuration']->value['product']->getPrice($_smarty_tpl->tpl_vars['tax']->value,(defined('NULL') ? constant('NULL') : null),2)),'.',','), ENT_QUOTES, 'UTF-8');?>
</span>
														<span class="taxe_unit">
															<?php if ($_smarty_tpl->tpl_vars['espace_pro']->value) {?>
																<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'HT','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

															<?php } else { ?>
																<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'TTC','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

															<?php }?>
														</span>
													</div>
												</div>
											</label>
										</td>
										<?php echo smarty_function_math(array('assign'=>'index','equation'=>'x + 1','x'=>$_smarty_tpl->tpl_vars['index']->value),$_smarty_tpl);?>

										<?php if ($_smarty_tpl->tpl_vars['index']->value%2 == 0 && count($_smarty_tpl->tpl_vars['configurations']->value) >= $_smarty_tpl->tpl_vars['index']->value) {?>
										</tr>
										<tr class="row">
										<?php }?>
									<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</tr>

							</tbody>
						</table>

						<div class="block_choice_color_visus_contain">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="block_choice_color_visus same-height"
										data-same-height-group="block_choice_color">

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['colors']->value, 'color', false, 'j');
$_smarty_tpl->tpl_vars['color']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['j']->value => $_smarty_tpl->tpl_vars['color']->value) {
$_smarty_tpl->tpl_vars['color']->do_else = false;
?>
											<div id="imgcolors-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['alias'], ENT_QUOTES, 'UTF-8');?>
" class="block_color_image imgcontainer"
												style="display: none;">
												<div class="block_visus">
													<img class="img-responsive" id="main-img-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['j']->value, ENT_QUOTES, 'UTF-8');?>
"
														src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme_selected_alias']->value, ENT_QUOTES, 'UTF-8');?>
/<?php echo htmlspecialchars((string) smarty_modifier_replace($_smarty_tpl->tpl_vars['color']->value['images'][0],'.png','-hd.png'), ENT_QUOTES, 'UTF-8');?>
"
														alt="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['alias'], ENT_QUOTES, 'UTF-8');?>
" />
												</div>
												<ul class="other_thumbnail clearfix">
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['color']->value['images'], 'img', false, 'k');
$_smarty_tpl->tpl_vars['img']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['img']->value) {
$_smarty_tpl->tpl_vars['img']->do_else = false;
?>
														<li>
															<a href="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme_selected_alias']->value, ENT_QUOTES, 'UTF-8');?>
/<?php echo htmlspecialchars((string) smarty_modifier_replace($_smarty_tpl->tpl_vars['img']->value,'.png','-hd.png'), ENT_QUOTES, 'UTF-8');?>
"
																id="link-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['j']->value, ENT_QUOTES, 'UTF-8');?>
-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
" class="img-fancybox"
																onmouseover="changeImg(<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['j']->value, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
,'<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['alias'], ENT_QUOTES, 'UTF-8');?>
')">
																<img class="img-responsive" id="img-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['j']->value, ENT_QUOTES, 'UTF-8');?>
-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
"
																	src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme_selected_alias']->value, ENT_QUOTES, 'UTF-8');?>
/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['img']->value, ENT_QUOTES, 'UTF-8');?>
"
																	alt="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['alias'], ENT_QUOTES, 'UTF-8');?>
" width="50" />
															</a>
														</li>
													<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												</ul>
											</div>
										<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										<p class="img_infos">NB: Les photos sont non contractuelles, les tapis de sol
											correspondront aux tapis d&rsquo;origine.</p>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="block_choice_color same-height"
										data-same-height-group="block_choice_color">
										<p class="title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Gamme','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>

										</p>
										<p class="subtitle">
											<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sélectionnez la couleur de votre choix','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

										</p>

										<?php $_smarty_tpl->_assignInScope('index', 0);?>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['colors']->value, 'color');
$_smarty_tpl->tpl_vars['color']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['color']->value) {
$_smarty_tpl->tpl_vars['color']->do_else = false;
?>
											<label for="radio-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['alias'], ENT_QUOTES, 'UTF-8');?>
"
												class="<?php if (!empty($_smarty_tpl->tpl_vars['color_selected']->value) && $_smarty_tpl->tpl_vars['color_selected']->value == $_smarty_tpl->tpl_vars['color']->value['id_couleur']) {?> active<?php } elseif (empty($_smarty_tpl->tpl_vars['colorChecked']->value)) {?> active<?php }
if ($_smarty_tpl->tpl_vars['color']->value['alias'] == 'carat-bleu' || $_smarty_tpl->tpl_vars['color']->value['alias'] == 'carat-rouge') {?> twolines<?php }?>"
												id="link-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['index']->value, ENT_QUOTES, 'UTF-8');?>
" onclick="loadColor('<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['alias'], ENT_QUOTES, 'UTF-8');?>
',<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['index']->value, ENT_QUOTES, 'UTF-8');?>
)">
												<span class="custom-radio">
													<input type="radio" name="color" id="radio-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['alias'], ENT_QUOTES, 'UTF-8');?>
"
														value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['id_couleur'], ENT_QUOTES, 'UTF-8');?>
" data-color_alias="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['alias'], ENT_QUOTES, 'UTF-8');?>
"
														data-background="url('<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
colors/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['image'], ENT_QUOTES, 'UTF-8');?>
"
														data-color_name="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['value'], ENT_QUOTES, 'UTF-8');?>
"
														<?php if (!empty($_smarty_tpl->tpl_vars['color_selected']->value) && $_smarty_tpl->tpl_vars['color_selected']->value == $_smarty_tpl->tpl_vars['color']->value['id_couleur']) {?>checked="checked"
															<?php $_smarty_tpl->_assignInScope('colorChecked', true);?>
														<?php } elseif (empty($_smarty_tpl->tpl_vars['colorChecked']->value)) {?>checked="checked"
														<?php $_smarty_tpl->_assignInScope('colorChecked', true);?> <?php }?> />
													<span></span>
												</span>
												<span class="color_palette"
													style="background: url('<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
colors/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['image'], ENT_QUOTES, 'UTF-8');?>
') no-repeat 0 3px"></span>
												<span class="color_name"><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['value'], ENT_QUOTES, 'UTF-8');?>
</span>
												<?php echo smarty_function_math(array('assign'=>'index','equation'=>'x + 1','x'=>$_smarty_tpl->tpl_vars['index']->value),$_smarty_tpl);?>

											</label>
										<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<form class="block_choice" action="" method="post">
						<div class="block_recap_config">
							<p class="title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Récapitulatif','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>
							<ul>
								<li><span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Marque :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
									<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'ucfirst' ][ 0 ], array( $_smarty_tpl->tpl_vars['marque_selected_nom']->value )), ENT_QUOTES, 'UTF-8');?>
</li>
								<li><span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Modèle :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span> <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['modele_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>
</li>
								<li><span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Finition :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
									<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Gamme','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>
</li>
								<li>
									<span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Configuration :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
									<div class="recap_choice_config">
										<div class="row">
											<div class="img_choice col-xs-12 col-sm-3 col-md-3 text-center">
												<img class="img-responsive hide" src="" alt="" />
											</div>
											<div class="details_choice_text col-xs-12 col-sm-8 col-md-8"></div>
										</div>
									</div>
								</li>
								<li>
									<span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Coloris :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
									<span class="color_palette" style=""></span>
									<span class="color_palette_text"></span>
								</li>
							</ul>
							<div class="price_cta text-center">
								<div class="price_container"
									<?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_COOKIE['confidential_mode'],"html" )) == "ON") {?> style="display:none"
									<?php }?>>
									<span class="price"></span>
									<span class="taxe_unit"></span>
								</div>

								<input type="hidden" name="id_conf" id="surmesure-id_conf" value="" />
								<input type="hidden" name="id_product" id="surmesure-id_product" value="" />
								<input type="hidden" name="color" id="surmesure-color" value="" />
								<input type="hidden" name="price" id="surmesure-price" value="" />
								<input type="submit" value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Valider mon choix','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
"
									class="btn-primary" />
							</div>
						</div>

						<div class="rea_bloc">
							<img class="img-responsive" src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['module_media_base_url']->value, ENT_QUOTES, 'UTF-8');?>
img/rea_img.png" alt="" />
							<span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Expédié sous 5 jours ouvrés','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="separator"></div>
		<div class="block_feature_block">
			<div class="rte">
				<div class="data-sheet">
					<p><span class="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Qualité moquettte :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span><span
							class="value"> <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['qualite_moquette'], ENT_QUOTES, 'UTF-8');?>
</span></p>
					<p><span class="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Qualité contour :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span><span
							class="value"> <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['qualite_contour'], ENT_QUOTES, 'UTF-8');?>
</span></p>
					<p><span class="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Qualité des matériaux :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span><span
							class="value"> <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['qualite_materiaux'], ENT_QUOTES, 'UTF-8');?>
</span></p>
					<p><span class="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Finition sous-couche :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span><span
							class="value"> <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['sous_couche'], ENT_QUOTES, 'UTF-8');?>
</span></p>
					<p><span class="name"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Coloris disponibles','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span><span
							class="value"> <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['coloris'], ENT_QUOTES, 'UTF-8');?>
</span></p>
				</div>
				<ul>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['gamme']->value['plus_produit'], 'plus');
$_smarty_tpl->tpl_vars['plus']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['plus']->value) {
$_smarty_tpl->tpl_vars['plus']->do_else = false;
?>
						<li><?php echo $_smarty_tpl->tpl_vars['plus']->value;?>
</li>
					<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</ul>

				<?php if ($_smarty_tpl->tpl_vars['gamme_details']->value['id_gamme'] == 3262) {?> 
					<p>Les tapis sur mesure Automotoboutic.com sont coup&eacute;s aux dimensions exactes de votre voiture.
						Les tapis auto &eacute;pouseront donc parfaitement l&rsquo;int&eacute;rieur de votre
						v&eacute;hicule.</p>
					<p>Les tapis auto sont pr&eacute;vus pour prot&eacute;ger des salissures et de l&rsquo;usure
						int&eacute;rieure de sa voiture. Lorsque nous conduisons, le frottement de nos pieds met le plancher
						de notre v&eacute;hicule &agrave; rude &eacute;preuve. C&rsquo;est pour cela que nos tapis auto
						conducteur dispose d&rsquo;une talonnette de renfort afin d&rsquo;allonger la dur&eacute;e de vie du
						tapis.</p>
					<p>Pour une conduite en toute s&eacute;curit&eacute;, les tapis auto sont munis d&rsquo;une sous-couche
						antid&eacute;rapante et d&rsquo;un syst&egrave;me de fixation identique &agrave; votre voiture. Cela
						permet d&rsquo;&eacute;viter que votre tapis auto glisse pendant la conduite.</p>
					<p>Les tapis de sol sont indispensables pour que l&rsquo;int&eacute;rieur de la voiture soit toujours
						impeccable. L&rsquo;entretien des tapis auto est tr&egrave;s facile, un seul coup d&rsquo;aspirateur
						suffit.</p>
					<p>Les tapis sur mesure Basique sont fabriqu&eacute;s en moquette aiguillet&eacute;. La densit&eacute;
						du tapis auto est de 1700g/m&sup2;.</p>
					<p>Les tapis auto sont fabriqu&eacute;s en Europe.</p>
					<p>NB : Les photos sont non contractuelles, les tapis de sol correspondront aux tapis d&rsquo;origine.
					</p>

				<?php } elseif ($_smarty_tpl->tpl_vars['gamme_details']->value['id_gamme'] == 3265) {?> 
					<p>Les tapis sur mesure Automotoboutic.com sont coup&eacute;s aux dimensions exactes de votre voiture.
						Les tapis auto &eacute;pouseront donc parfaitement l&rsquo;int&eacute;rieur de votre
						v&eacute;hicule.</p>
					<p>Les tapis auto sont pr&eacute;vus pour prot&eacute;ger des salissures et de l&rsquo;usure
						int&eacute;rieure de sa voiture. Lorsque nous conduisons, le frottement de nos pieds met le plancher
						de notre v&eacute;hicule &agrave; rude &eacute;preuve. C&rsquo;est pour cela que nos tapis auto
						conducteur dispose d&rsquo;une talonnette de renfort afin d&rsquo;allonger la dur&eacute;e de vie du
						tapis.</p>
					<p>Pour une conduite en toute s&eacute;curit&eacute;, les tapis auto sont munis d&rsquo;une sous-couche
						antid&eacute;rapante et d&rsquo;un syst&egrave;me de fixation identique &agrave; votre voiture. Cela
						permet d&rsquo;&eacute;viter que votre tapis auto glisse pendant la conduite.</p>
					<p>Les tapis sur mesure Premium sont fabriqu&eacute;s en moquette tuft&eacute;, tr&egrave;s
						r&eacute;sistante aux frottements. La densit&eacute; du tapis auto est de 2150g/m&sup2;.</p>
					<p>Les tapis auto ont pour finition un surjet fil assorti au coloris de la moquette.</p>
					<p>L&rsquo;entretien des tapis auto est tr&egrave;s rapide et facile gr&acirc;ce &agrave; la moquette
						tuft&eacute;. Un seul coup d&rsquo;aspirateur suffit.</p>
					<p>Les tapis auto sont fabriqu&eacute;s en Europe.</p>
					<p>NB : Les photos sont non contractuelles, les tapis de sol correspondront aux tapis d&rsquo;origine.
					</p>

				<?php } elseif ($_smarty_tpl->tpl_vars['gamme_details']->value['id_gamme'] == 3266) {?> 
					<p>Les tapis sur mesure Automotoboutic.com sont coup&eacute;s aux dimensions exactes de votre voiture.
						Les tapis auto &eacute;pouseront donc parfaitement l&rsquo;int&eacute;rieur de votre
						v&eacute;hicule.</p>
					<p>Les tapis auto sont pr&eacute;vus pour prot&eacute;ger des salissures et de l&rsquo;usure
						int&eacute;rieure de sa voiture. Lorsque nous conduisons, le frottement de nos pieds met le plancher
						de notre v&eacute;hicule &agrave; rude &eacute;preuve. C&rsquo;est pour cela que nos tapis auto
						conducteur dispose d&rsquo;une talonnette de renfort afin d&rsquo;allonger la dur&eacute;e de vie du
						tapis.</p>
					<p>Pour une conduite en toute s&eacute;curit&eacute;, les tapis auto sont munis d&rsquo;une sous-couche
						antid&eacute;rapante et d&rsquo;un syst&egrave;me de fixation identique &agrave; votre voiture. Cela
						permet d&rsquo;&eacute;viter que votre tapis auto glisse pendant la conduite.</p>
					<p>Les tapis de sol sont indispensables pour que l&rsquo;int&eacute;rieur de la voiture soit toujours
						impeccable. L&rsquo;entretien des tapis auto est tr&egrave;s facile, un seul coup d&rsquo;aspirateur
						suffit.</p>
					<p>Les tapis sur mesure Grand Tourisme sont fabriqu&eacute;s en moquette aiguillet&eacute;, tr&egrave;s
						r&eacute;sistante aux frottements. La densit&eacute; du tapis auto est de 1950g/m&sup2;.</p>
					<p>Les tapis auto ont pour finition un surjet fil assorti au coloris de la moquette.</p>
					<p>Les tapis auto sont fabriqu&eacute;s en Europe.</p>
					<p>NB : Les photos sont non contractuelles, les tapis de sol correspondront aux tapis d&rsquo;origine.
					</p>

				<?php } elseif ($_smarty_tpl->tpl_vars['gamme_details']->value['id_gamme'] == 3264) {?> 
					<p>Les tapis sur mesure Automotoboutic.com sont coup&eacute;s aux dimensions exactes de votre voiture.
						Les tapis auto &eacute;pouseront donc parfaitement l&rsquo;int&eacute;rieur de votre
						v&eacute;hicule.</p>
					<p>Les tapis auto sont pr&eacute;vus pour prot&eacute;ger des salissures et de l&rsquo;usure
						int&eacute;rieure de sa voiture. Lorsque nous conduisons, le frottement de nos pieds met le plancher
						de notre v&eacute;hicule &agrave; rude &eacute;preuve. C&rsquo;est pour cela que nos tapis auto
						conducteur dispose d&rsquo;une talonnette de renfort afin d&rsquo;allonger la dur&eacute;e de vie du
						tapis.</p>
					<p>Pour une conduite en toute s&eacute;curit&eacute;, les tapis auto sont munis d&rsquo;une sous-couche
						antid&eacute;rapante et d&rsquo;un syst&egrave;me de fixation identique &agrave; votre voiture. Cela
						permet d&rsquo;&eacute;viter que votre tapis auto glisse pendant la conduite.</p>
					<p>Les tapis sur mesure Elite sont fabriqu&eacute;s en moquette tuft&eacute;, tr&egrave;s
						r&eacute;sistante aux frottements. La densit&eacute; du tapis auto est de 2500g/m&sup2;.</p>
					<p>Une ganse textile assortie au coloris de la moquette pour une finition parfaite des tapis auto sur
						mesure.</p>
					<p>L&rsquo;entretien des tapis auto est tr&egrave;s rapide et facile gr&acirc;ce &agrave; la moquette
						tuft&eacute;. Un seul coup d&rsquo;aspirateur suffit.</p>
					<p>Les tapis auto sont fabriqu&eacute;s en Europe.</p>
					<p>NB : Les photos sont non contractuelles, les tapis de sol correspondront aux tapis d&rsquo;origine.
					</p>

				<?php } elseif ($_smarty_tpl->tpl_vars['gamme_details']->value['id_gamme'] == 50171) {?> 
					<p>Les tapis sur mesure Automotoboutic.com sont coup&eacute;s aux dimensions exactes de votre voiture.
						Les tapis auto &eacute;pouseront donc parfaitement l&rsquo;int&eacute;rieur de votre
						v&eacute;hicule.</p>
					<p>Les tapis auto sont pr&eacute;vus pour prot&eacute;ger des salissures et de l&rsquo;usure
						int&eacute;rieure de sa voiture. Lorsque nous conduisons, le frottement de nos pieds met le plancher
						de notre v&eacute;hicule &agrave; rude &eacute;preuve. C&rsquo;est pour cela que nos tapis auto
						conducteur dispose d&rsquo;une talonnette de renfort afin d&rsquo;allonger la dur&eacute;e de vie du
						tapis.</p>
					<p>Pour une conduite en toute s&eacute;curit&eacute;, les tapis auto sont munis d&rsquo;une sous-couche
						antid&eacute;rapante et d&rsquo;un syst&egrave;me de fixation identique &agrave; votre voiture. Cela
						permet d&rsquo;&eacute;viter que votre tapis auto glisse pendant la conduite.</p>
					<p>Les tapis sur mesure Carat de tr&egrave;s haute qualit&eacute; sont fabriqu&eacute;s en moquette
						tuft&eacute;, tr&egrave;s r&eacute;sistante aux frottements. La densit&eacute; du tapis auto est de
						2200g/m&sup2;.</p>
					<p>Finition haut de gamme des tapis auto sur mesure avec une ganse en nubuck (simili cuir) avec
						surpiqure blanche, rouge ou bleu.</p>
					<p>L&rsquo;entretien des tapis auto est tr&egrave;s rapide et facile gr&acirc;ce &agrave; la moquette
						tuft&eacute;. Un seul coup d&rsquo;aspirateur suffit.</p>
					<p>Les tapis auto sont fabriqu&eacute;s en Europe.</p>
					<p>NB :Les photos sont non contractuelles, les tapis de sol correspondront aux tapis d&rsquo;origine.
					</p>

				<?php }?>
			</div>
		</div>
	</div><!-- end .container -->
</div><!-- end #step-3 -->
<?php
}
}
/* {/block 'process_step_4_content'} */
}
