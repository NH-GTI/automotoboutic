{block name='process_step_5_content'}
	<div class="setup-content block_custom" id="step-5">
		<div class="container">
			<p class="title_block">{l s='Validation' d='Modules.Surmesure.Shop'}</p>
			<div class="block_choice_recap">
				<div class="row recap-container">
					<div class="col-md-12">
						<div class="block_recap_config">
							<p class="title">{l s='Récapitulatif' d='Modules.Surmesure.Shop'}</p>
							<ul>
								<li><span>{l s='Marque :' d='Modules.Surmesure.Shop'}</span> {$marque_selected_nom}</li>
								<li><span>{l s='Modèle :' d='Modules.Surmesure.Shop'}</span> {$modele_selected_nom}</li>
								<li><span>{l s='Finition :' d='Modules.Surmesure.Shop'}</span>
									{l s='Gamme' d='Modules.Surmesure.Shop'} {$gamme_selected_nom}</li>
								<li>
									<span>{l s='Configuration :' d='Modules.Surmesure.Shop'}</span>
									<div class="recap_choice_config">
										<div class="row">
											<div class="img_choice col-xs-12 col-sm-3 col-md-3 text-center">
												<img class="img-responsive hide"
													src="{$surmesure_img_url}custom/configurations/{$conf_selected_img}"
													alt="{$conf_selected_nom nofilter}" />
											</div>
											<div class="details_choice_text col-xs-12 col-sm-8 col-md-8">
												{$conf_selected_desc nofilter}</div>
										</div>
									</div>
								</li>
								<li>
									<span>{l s='Coloris :' d='Modules.Surmesure.Shop'}</span>
									<span class="color_palette"
										style="background: url('{$surmesure_img_url}colors/{$color_img}') no-repeat 0 3px; display: inline-block; height: 25px; width: 25px;"></span>
									<span class="color_palette_text">{$color_nom}</span>
								</li>
								<li>
								</li>
							</ul>
							<form class="block_order_validation" method="post" action="">
								<div class="price_cta text-center">
									<div class="price_container"
										{if $smarty.cookies.confidential_mode|escape:"html" == "ON"} style="display:none"
										{/if}>
										<span class="price">{$product_price}</span>
										<span class="taxe_unit">{if $espace_pro}
												{l s='HT' d='Modules.Surmesure.Shop'}
											{else}
												{l s='TTC' d='Modules.Surmesure.Shop'}
											{/if}</span>
									</div>
									<input type="hidden" name="add" value="1" />
									<input type="hidden" name="id_product" value="{$product_id}" />
									<input type="hidden" name="qty" value="1" />
									<span class="submit_btn btn-primary">
										<input type="submit" value="{l s='Ajouter au panier' d='Modules.Surmesure.Shop'}"
											class="btn-order btn-primary" />
									</span>
								</div>
							</form>
						</div>
					</div>
					{* <div class="col-md-8">
						<form class="block_order_validation" method="post" action="">
							<div class="block_order_validation_form">
								<div class="row">
									<div class="block_img_carte_main col-xs-4 col-sm-5 col-md-3">	
										<div class="block_img_carte">
											<img class="img-responsive" src="{$module_media_base_url}img/carte_grise.jpg" alt="" />
										</div>
									</div>
									<div class="col-xs-8 col-sm-7 col-md-5 block_zoom_textinfos_endstep">	
										<div class="block_zoom_textinfos">
											<div class="block_img_zoom">
												<img class="img-responsive" src="{$module_media_base_url}img/zoom_carte_grise.jpg" alt="" />
											</div>
											<p class="text_infos">
												<strong>{l s='Vous ne connaissez pas l\'année de mise en circulation de votre véhicule ?' d='Modules.Surmesure.Shop'}</strong><br />
												{l s='La date de 1ère immatriculation de votre véhicule nous permet de déterminer avec excatitude le modèle de votre véhicule. Vous la trouverez sur' d='Modules.Surmesure.Shop'} <strong>{l s='votre carte grise.' d='Modules.Surmesure.Shop'}</strong>
											</p>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-4 block_select_date_endstep">	
										<p>
											{l s='Pour terminer, veuillez sélectionner ' d='Modules.Surmesure.Shop'}
											<strong>{l s='La date de 1ère mise en circulation de votre véhicule :' d='Modules.Surmesure.Shop'}</strong>
										</p>
										<div class="block_select_date">
											<span class="custom_select">
												<select name="day" class="form-control form-control-select">
													{section name=boucle start=1 loop=32 step=1}
													<option value="{$smarty.section.boucle.index}">{$smarty.section.boucle.index}</option>
													{/section}
												</select>
											</span>
											<span class="custom_select">
												<select name="month" class="form-control form-control-select">
													{section name=boucle start=1 loop=13 step=1}
													<option value="{$smarty.section.boucle.index}">{$smarty.section.boucle.index}</option>
													{/section}
												</select>
											</span>
											<span class="custom_select custom_select_year">
												<select name="year" class="form-control form-control-select">
													{foreach $years item='year'}
													<option value="{$year}">{$year}</option>
													{/foreach}
												</select>
											</span>
											<div class="clear"></div>
										</div>

										<input type="hidden" name="add" value="1" />
										<input type="hidden" name="id_product" value="{$product_id}" />
										<input type="hidden" name="qty" value="1" />
										<span class="submit_btn btn-primary">
											<input 
											type="submit" 
											value="{l s='Commander' d='Modules.Surmesure.Shop'}" 
											class="btn-order btn-primary"  />
										</span>
									</div>

								</div>
							</div>
						</form>
					</div> *}
				</div>
			</div>
		</div><!-- end .container -->
	</div><!-- end #step-4 -->
{/block}