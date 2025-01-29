<?php
/* Smarty version 4.3.4, created on 2025-01-27 14:46:59
  from '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-6.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_67978e53b7a177_29901007',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5c938de09546982ced2e3b063282ea47b71b12b4' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-6.tpl',
      1 => 1732524662,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67978e53b7a177_29901007 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_40412551167978e53b535a2_19245947', 'process_step_5_content');
}
/* {block 'process_step_5_content'} */
class Block_40412551167978e53b535a2_19245947 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_5_content' => 
  array (
    0 => 'Block_40412551167978e53b535a2_19245947',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<?php if ($_smarty_tpl->tpl_vars['display_popup']->value) {?>
	<div class="popup" data-popup="popup-1">
		<div class="popup-inner">
				<div id="popup-overlay"></div>

				<div id="blocksurmesure-modal">

					<p class="title"><i class="material-icons">&#xE876;</i> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Vos tapis sur mesure ont été ajoutés à votre panier :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>

					<p class="subtitle">
						<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Pour votre','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['marque_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['modele_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>
 
					</p>
					<hr/>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 block_popup_config">
							<b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Configuration','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</b><br/><br/>
							<div class="row">
								<div class="col-xs-12 col-sm-4 col-md-4">
									<div class="img_choice text-center">
										<img class="img-responsive hide" src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/configurations/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['conf_selected_img']->value, ENT_QUOTES, 'UTF-8');?>
" alt="<?php echo $_smarty_tpl->tpl_vars['conf_selected_nom']->value;?>
" />
									</div>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8">
									<?php echo $_smarty_tpl->tpl_vars['conf_selected_desc']->value;?>

								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="block_content_finition">
								<b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Finition','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</b> : <b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Gamme','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
 <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['gamme_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>
</b>
								<br/><br/>
								 <span class="color_palette" style="background: url('<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
colors/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color_img']->value, ENT_QUOTES, 'UTF-8');?>
') no-repeat 0 3px; display: inline-block; height: 25px; width: 25px;"></span>
								 <span class="color_palette_text"><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color_nom']->value, ENT_QUOTES, 'UTF-8');?>
</span>
							</div>
						</div>
					</div>
					<br/><br/>
					<div class="buttons">
						<a class="btn btn-primary pull-left" href="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['link']->value->getModuleLink('lm_surmesure','surmesure',array('step'=>8)), ENT_QUOTES, 'UTF-8');?>
">
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Continuer mes achats','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

						</a>
                        <a class="btn btn-primary pull-right" href="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['link']->value->getModuleLink('lm_surmesure','surmesure',array('step'=>7)), ENT_QUOTES, 'UTF-8');?>
">
							<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Voir mon panier','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

						</a>
					</div>
					<?php if ((isset($_smarty_tpl->tpl_vars['accessories']->value)) && $_smarty_tpl->tpl_vars['accessories']->value) {?>
						<!-- accessories -->
						<!-- three-columns -->
						<div id="popup_feature">
							<div class="subtitle"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Les clients ayant acheté ce produit ont également acheté :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</div>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['accessories']->value, 'accessory', false, NULL, 'accessories_list', array (
));
$_smarty_tpl->tpl_vars['accessory']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['accessory']->value) {
$_smarty_tpl->tpl_vars['accessory']->do_else = false;
?>
							<?php $_smarty_tpl->_assignInScope('accessoryLink', $_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['accessory']->value['id_product'],$_smarty_tpl->tpl_vars['accessory']->value['link_rewrite'],$_smarty_tpl->tpl_vars['accessory']->value['category']));?>
							<div class="box">
								<div class="holder">
									<div class="frame">
										<a href="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['accessory']->value['link'], ENT_QUOTES, 'UTF-8');?>
" title="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['accessory']->value['name'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
											<img 
												src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['accessory']->value['link_rewrite'],$_smarty_tpl->tpl_vars['accessory']->value['id_image'],'home'), ENT_QUOTES, 'UTF-8');?>
" 
												height="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['homeSize']->value['height'], ENT_QUOTES, 'UTF-8');?>
" width="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['homeSize']->value['width'], ENT_QUOTES, 'UTF-8');?>
" 
												alt="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['accessory']->value['name'],37,'...' )),'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" />
										</a>
										<div class="description">
											<h2>
												<a 
													href="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['accessory']->value['link'], ENT_QUOTES, 'UTF-8');?>
" 
													title="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['accessory']->value['name'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
													<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['accessory']->value['name'],37,'...' )),'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>

												</a>
											</h2>
											<p><?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( preg_replace('!<[^>]*?>!', ' ', (string) $_smarty_tpl->tpl_vars['accessory']->value['description_short']),90,'...' )), ENT_QUOTES, 'UTF-8');?>
</p>
											<span class="watch">
												<a 
													href="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['accessory']->value['link'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
" 
													title="<?php echo htmlspecialchars((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['accessory']->value['name'],'htmlall','UTF-8' )), ENT_QUOTES, 'UTF-8');?>
">
													<img src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['img_dir']->value, ENT_QUOTES, 'UTF-8');?>
/watch-product.png" alt="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Voir le produit','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
" />
												</a>
											</span>
											<strong class="price">
												<?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value) {
echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['accessory']->value['price'], ENT_QUOTES, 'UTF-8');?>

												<?php } else {
echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['accessory']->value['price_tax_exc'], ENT_QUOTES, 'UTF-8');
}?>
												<small><?php if ($_smarty_tpl->tpl_vars['espace_pro']->value) {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'HT','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );
} else {
echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'TTC','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );
}?></small>
											</strong>
											<div class="addtocart-button">
												<form action="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['urls']->value['pages']['cart'], ENT_QUOTES, 'UTF-8');?>
" method="post">
													<input type="hidden" name="token" value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['static_token']->value, ENT_QUOTES, 'UTF-8');?>
" />
													<input type="hidden" value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['accessory']->value['id_product'], ENT_QUOTES, 'UTF-8');?>
" name="id_product" />
													<input type="hidden" class="input-group form-control" value="1" name="qty" />
													<button data-button-action="add-to-cart" class="btn btn-primary">
														<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Ajouter au panier','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

													</button>
												  </form>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</div>
					<?php }?>

				</div>
				<a class="popup-close" data-popup-close="popup-1" href="#">x</a>
			</div>
		</div>
	<?php }
}
}
/* {/block 'process_step_5_content'} */
}
