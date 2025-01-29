
{block name='process_step_5_content'}
	{if $display_popup}
	<div class="popup" data-popup="popup-1">
		<div class="popup-inner">
				<div id="popup-overlay"></div>

				<div id="blocksurmesure-modal">

					<p class="title"><i class="material-icons">&#xE876;</i> {l s='Vos tapis sur mesure ont été ajoutés à votre panier :' d='Modules.Surmesure.Shop'}</p>

					<p class="subtitle">
						{l s='Pour votre' d='Modules.Surmesure.Shop'} {$marque_selected_nom} {$modele_selected_nom} 
						- {l s='année' d='Modules.Surmesure.Shop'} {$custom_year_car} 
					</p>
					<hr/>
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 block_popup_config">
							<b>{l s='Configuration' d='Modules.Surmesure.Shop'}</b><br/><br/>
							<div class="row">
								<div class="col-xs-12 col-sm-4 col-md-4">
									<div class="img_choice text-center">
										<img class="img-responsive hide" src="{$surmesure_img_url}custom/configurations/{$conf_selected_img}" alt="{$conf_selected_nom nofilter}" />
									</div>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8">
									{$conf_selected_desc nofilter}
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="block_content_finition">
								<b>{l s='Finition' d='Modules.Surmesure.Shop'}</b> : <b>{l s='Gamme' d='Modules.Surmesure.Shop'} {$gamme_selected_nom}</b>
								<br/><br/>
								 <span class="color_palette" style="background: url('{$surmesure_img_url}colors/{$color_img}') no-repeat 0 3px; display: inline-block; height: 25px; width: 25px;"></span>
								 <span class="color_palette_text">{$color_nom}</span>
							</div>
						</div>
					</div>
					<br/><br/>
					<div class="buttons">
						<a class="btn btn-primary pull-left" href="{$link->getModuleLink('lm_surmesure', 'surmesure', ['step' => 8])}">
							{l s='Continuer mes achats' d='Modules.Surmesure.Shop'}
						</a>
                        <a class="btn btn-primary pull-right" href="{$link->getModuleLink('lm_surmesure', 'surmesure', ['step' => 7])}">
							{l s='Voir mon panier' d='Modules.Surmesure.Shop'}
						</a>
					</div>
					{if isset($accessories) AND $accessories}
						<!-- accessories -->
						<!-- three-columns -->
						<div id="popup_feature">
							<div class="subtitle">{l s='Les clients ayant acheté ce produit ont également acheté :' d='Modules.Surmesure.Shop'}</div>

							{foreach from=$accessories item=accessory name=accessories_list}
							{assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
							<div class="box">
								<div class="holder">
									<div class="frame">
										<a href="{$accessory.link}" title="{$accessory.name|escape:'htmlall':'UTF-8'}">
											<img 
												src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'home')}" 
												height="{$homeSize.height}" width="{$homeSize.width}" 
												alt="{$accessory.name|truncate:37:'...'|escape:'htmlall':'UTF-8'}" />
										</a>
										<div class="description">
											<h2>
												<a 
													href="{$accessory.link}" 
													title="{$accessory.name|escape:'htmlall':'UTF-8'}">
													{$accessory.name|truncate:37:'...'|escape:'htmlall':'UTF-8'}
												</a>
											</h2>
											<p>{$accessory.description_short|strip_tags|truncate:90:'...'}</p>
											<span class="watch">
												<a 
													href="{$accessory.link|escape:'htmlall':'UTF-8'}" 
													title="{$accessory.name|escape:'htmlall':'UTF-8'}">
													<img src="{$img_dir}/watch-product.png" alt="{l s='Voir le produit' d='Modules.Surmesure.Shop'}" />
												</a>
											</span>
											<strong class="price">
												{if !$priceDisplay}{convertPrice price=$accessory.price}
												{else}{convertPrice price=$accessory.price_tax_exc}{/if}
												<small>{if $espace_pro}{l s='HT' d='Modules.Surmesure.Shop'}{else}{l s='TTC' d='Modules.Surmesure.Shop'}{/if}</small>
											</strong>
											<div class="addtocart-button">
												<form action="{$urls.pages.cart}" method="post">
													<input type="hidden" name="token" value="{$static_token}" />
													<input type="hidden" value="{$accessory.id_product}" name="id_product" />
													<input type="hidden" class="input-group form-control" value="1" name="qty" />
													<button data-button-action="add-to-cart" class="btn btn-primary">
														{l s='Ajouter au panier' d='Modules.Surmesure.Shop'}
													</button>
												  </form>
											</div>
										</div>
									</div>
								</div>
							</div>
							{/foreach}
						</div>
					{/if}

				</div>
				<a class="popup-close" data-popup-close="popup-1" href="#">x</a>
			</div>
		</div>
	{/if}
{/block}