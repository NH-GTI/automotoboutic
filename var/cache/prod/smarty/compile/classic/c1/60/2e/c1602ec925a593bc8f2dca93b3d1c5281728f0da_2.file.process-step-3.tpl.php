<?php
/* Smarty version 4.3.4, created on 2025-01-27 14:46:46
  from '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-3.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_67978e460d9885_48260524',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c1602ec925a593bc8f2dca93b3d1c5281728f0da' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-3.tpl',
      1 => 1732524662,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67978e460d9885_48260524 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_87307850067978e460b5589_93708755', 'process_step_2_content');
?>

<?php }
/* {block 'process_step_2_content'} */
class Block_87307850067978e460b5589_93708755 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_2_content' => 
  array (
    0 => 'Block_87307850067978e460b5589_93708755',
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



	<div class="setup-content block_custom block_model_step3" id="step-3">
		<div class="container">
			<p class="title_block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Sélectionnez la finition de votre tapis','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>
		</div>
		<section>
			<ul class="gamme-container">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['gammes']->value, 'gamme', false, NULL, 'gammesLoop', array (
));
$_smarty_tpl->tpl_vars['gamme']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['gamme']->value) {
$_smarty_tpl->tpl_vars['gamme']->do_else = false;
?>
					<li class="product">
						<div class="model_listing_block <?php if ($_smarty_tpl->tpl_vars['gamme']->value['id_gamme'] == 3264) {?>gamme_elite<?php }?>">
							<div class="top-info">
								<div class="short_desc_data" data-same-height-group="short_desc_data">
									<?php if ($_smarty_tpl->tpl_vars['gamme']->value['id_gamme'] == 3264) {?><span style="
										background-color: #e9540d;
										font-size: 16px;
										display: block;
										color: white;
										padding: 5px 0 0 13px;
										text-decoration: underline;
									">La plus choisie par nos clients</span><?php }?>
									<p class="title_name_model same-height" data-same-height-group="short_desc_data_title">
										<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Gamme','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['gamme']->value['value'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
 <?php
$_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['foo']->step = 1;$_smarty_tpl->tpl_vars['foo']->total = (int) ceil(($_smarty_tpl->tpl_vars['foo']->step > 0 ? $_smarty_tpl->tpl_vars['gamme']->value['rating']+1 - (1) : 1-($_smarty_tpl->tpl_vars['gamme']->value['rating'])+1)/abs($_smarty_tpl->tpl_vars['foo']->step));
if ($_smarty_tpl->tpl_vars['foo']->total > 0) {
for ($_smarty_tpl->tpl_vars['foo']->value = 1, $_smarty_tpl->tpl_vars['foo']->iteration = 1;$_smarty_tpl->tpl_vars['foo']->iteration <= $_smarty_tpl->tpl_vars['foo']->total;$_smarty_tpl->tpl_vars['foo']->value += $_smarty_tpl->tpl_vars['foo']->step, $_smarty_tpl->tpl_vars['foo']->iteration++) {
$_smarty_tpl->tpl_vars['foo']->first = $_smarty_tpl->tpl_vars['foo']->iteration === 1;$_smarty_tpl->tpl_vars['foo']->last = $_smarty_tpl->tpl_vars['foo']->iteration === $_smarty_tpl->tpl_vars['foo']->total;?> &#11088; <?php }
}
?></p>
									<div class="short_desc">
										<p>“<?php if ($_smarty_tpl->tpl_vars['gamme']->value['avis']) {
echo $_smarty_tpl->tpl_vars['gamme']->value['avis'];
}?>”</p>
									</div>
								</div> <!-- .top-info -->
							</div> <!-- .top-info -->
							<div class="container_carpet_type">
								<div class="bloc_visus">
									<div>
										<div class="large_img">
											<?php $_smarty_tpl->_assignInScope('color', $_smarty_tpl->tpl_vars['gamme']->value['colors'][0]);?>
											<img id="main-img-<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['alias'], ENT_QUOTES, 'UTF-8');?>
" class="img-responsive"
												src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['alias'], ENT_QUOTES, 'UTF-8');?>
/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['main_image'], ENT_QUOTES, 'UTF-8');?>
"
												alt="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['value'], ENT_QUOTES, 'UTF-8');?>
" />
										</div>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['gamme']->value['colors'], 'details', false, 'k');
$_smarty_tpl->tpl_vars['details']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['details']->value) {
$_smarty_tpl->tpl_vars['details']->do_else = false;
?>
											<div class="block_img_thubnail block_img_thubnail_<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['k']->value > 0) {?>style="display:none"
												<?php }?>>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['details']->value['array_images'], 'thb');
$_smarty_tpl->tpl_vars['thb']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['thb']->value) {
$_smarty_tpl->tpl_vars['thb']->do_else = false;
?>
													<a onclick="changeImg('<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['alias'], ENT_QUOTES, 'UTF-8');?>
','<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['thb']->value, ENT_QUOTES, 'UTF-8');?>
');" href="javascript:;">
														<img src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['alias'], ENT_QUOTES, 'UTF-8');?>
/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['thb']->value, ENT_QUOTES, 'UTF-8');?>
" width="110"
															height="110" alt="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['main_image'], ENT_QUOTES, 'UTF-8');?>
" class="img-responsive" />
													</a>
												<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											</div>
										<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										<div class="block_other_thubnail">
											<div class="same-height_img">
												<span
													class="title_thumb"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Autres coloris','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
												<ul class="other_thumbnail">
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['gamme']->value['colors'], 'color', false, 'k');
$_smarty_tpl->tpl_vars['color']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['color']->value) {
$_smarty_tpl->tpl_vars['color']->do_else = false;
?>
														<li>
															<a onclick="changeImg('<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['alias'], ENT_QUOTES, 'UTF-8');?>
','<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['main_image'], ENT_QUOTES, 'UTF-8');?>
','block_img_thubnail_<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['k']->value, ENT_QUOTES, 'UTF-8');?>
');"
																href="javascript:;">
																<img src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['alias'], ENT_QUOTES, 'UTF-8');?>
/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['main_image'], ENT_QUOTES, 'UTF-8');?>
"
																	width="63" height="63" alt="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color']->value['main_image'], ENT_QUOTES, 'UTF-8');?>
"
																	class="img-responsive" />
															</a>
														</li>
													<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<ul class="cd-features-list">
																			<li class="" data-same-height-group="block_feat_model_QM">
											<div class="block_feat_model_custom first">
												<span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Qualité moquette :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
												<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['qualite_moquette'], ENT_QUOTES, 'UTF-8');?>

											</div>
										</li>
										<li class="" data-same-height-group="block_feat_model_QC">
											<div class="block_feat_model_custom">
												<span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Qualité contour :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
												<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['qualite_contour'], ENT_QUOTES, 'UTF-8');?>

											</div>
										</li>
										<li class="" data-same-height-group="block_feat_model_QDM">
											<div class="block_feat_model_custom">
												<span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Qualité des matériaux :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
												<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['qualite_materiaux'], ENT_QUOTES, 'UTF-8');?>

											</div>
										</li>
										<li class="" data-same-height-group="block_feat_model_FSC">
											<div class="block_feat_model_custom last">
												<span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Finition sous-couche :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
												<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['sous_couche'], ENT_QUOTES, 'UTF-8');?>

											</div>
										</li>
																														<li class="block_date_pricectabtn" data-same-height-group="block_date_pricecta">
											<?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_COOKIE['confidential_mode'],"html" )) != "ON") {?>
												<div class="block_price_cta">
													<p class="price_container">
																											</p>
													<span class="small_text_price">
												<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['surbase'], ENT_QUOTES, 'UTF-8');?>

											</span>
												</div>
											<?php }?>
											<div class="block_price_cta">
												<form name="selectgam" method="post" action="">
													<input type="hidden" name="id_gamme" value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['id_gamme'], ENT_QUOTES, 'UTF-8');?>
" />
													<input type="submit" value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme']->value['choisi'], ENT_QUOTES, 'UTF-8');?>
" class="btn-primary nextBtnstep" />
												</form>
											</div>
										</li>
																			</ul>
								</div>
							</div>
						</li> <!-- .product -->
					<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					<div id="dialog-img-popup" title="" style="display: none;">
						<img id="image_popup" src="" />
					</div>
				</ul> <!-- .cd-products-columns -->

			</section> <!-- .cd-products-comparison-table -->

		</div><!-- end #step-2 -->
	<?php
}
}
/* {/block 'process_step_2_content'} */
}
