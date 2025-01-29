<?php
/* Smarty version 4.3.4, created on 2025-01-27 14:46:57
  from '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-5.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_67978e51619fc5_81922054',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a4da369daf0dc3be5385a4fc3681c79f6bd8a9ec' => 
    array (
      0 => '/var/www/html/automotoboutic/modules/lm_surmesure/views/templates/widget/process-step-5.tpl',
      1 => 1732524662,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67978e51619fc5_81922054 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_101092227267978e5160ad98_84276684', 'process_step_5_content');
}
/* {block 'process_step_5_content'} */
class Block_101092227267978e5160ad98_84276684 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'process_step_5_content' => 
  array (
    0 => 'Block_101092227267978e5160ad98_84276684',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<div class="setup-content block_custom" id="step-5">
		<div class="container">
			<p class="title_block"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Validation','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>
			<div class="block_choice_recap">
				<div class="row recap-container">
					<div class="col-md-12">
						<div class="block_recap_config">
							<p class="title"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Récapitulatif','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</p>
							<ul>
								<li><span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Marque :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span> <?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['marque_selected_nom']->value, ENT_QUOTES, 'UTF-8');?>
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
												<img class="img-responsive hide"
													src="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
custom/configurations/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['conf_selected_img']->value, ENT_QUOTES, 'UTF-8');?>
"
													alt="<?php echo $_smarty_tpl->tpl_vars['conf_selected_nom']->value;?>
" />
											</div>
											<div class="details_choice_text col-xs-12 col-sm-8 col-md-8">
												<?php echo $_smarty_tpl->tpl_vars['conf_selected_desc']->value;?>
</div>
										</div>
									</div>
								</li>
								<li>
									<span><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Coloris :','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
</span>
									<span class="color_palette"
										style="background: url('<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['surmesure_img_url']->value, ENT_QUOTES, 'UTF-8');?>
colors/<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color_img']->value, ENT_QUOTES, 'UTF-8');?>
') no-repeat 0 3px; display: inline-block; height: 25px; width: 25px;"></span>
									<span class="color_palette_text"><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['color_nom']->value, ENT_QUOTES, 'UTF-8');?>
</span>
								</li>
								<li>
								</li>
							</ul>
							<form class="block_order_validation" method="post" action="">
								<div class="price_cta text-center">
									<div class="price_container"
										<?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_COOKIE['confidential_mode'],"html" )) == "ON") {?> style="display:none"
										<?php }?>>
										<span class="price"><?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['product_price']->value, ENT_QUOTES, 'UTF-8');?>
</span>
										<span class="taxe_unit"><?php if ($_smarty_tpl->tpl_vars['espace_pro']->value) {?>
												<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'HT','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

											<?php } else { ?>
												<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'TTC','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>

											<?php }?></span>
									</div>
									<input type="hidden" name="add" value="1" />
									<input type="hidden" name="id_product" value="<?php echo htmlspecialchars((string) $_smarty_tpl->tpl_vars['product_id']->value, ENT_QUOTES, 'UTF-8');?>
" />
									<input type="hidden" name="qty" value="1" />
									<span class="submit_btn btn-primary">
										<input type="submit" value="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Ajouter au panier','d'=>'Modules.Surmesure.Shop'),$_smarty_tpl ) );?>
"
											class="btn-order btn-primary" />
									</span>
								</div>
							</form>
						</div>
					</div>
									</div>
			</div>
		</div><!-- end .container -->
	</div><!-- end #step-4 -->
<?php
}
}
/* {/block 'process_step_5_content'} */
}
