{block name='process_step_2_content'}

	<script type="text/javascript">
		var surmesure_img_url = "{$surmesure_img_url}custom/";
	</script>



	<div class="setup-content block_custom block_model_step3" id="step-3">
		<div class="container">
			<p class="title_block">{l s='Sélectionnez la finition de votre tapis' d='Modules.Surmesure.Shop'}</p>
		</div>
		<section>
			<ul class="gamme-container">
				{foreach from=$gammes item=gamme name=gammesLoop}
					<li class="product">
						<div class="model_listing_block {if $gamme.id_gamme == 3264}gamme_elite{/if}">
							<div class="top-info">
								<div class="short_desc_data" data-same-height-group="short_desc_data">
									{if $gamme.id_gamme == 3264}<span style="
										background-color: #e9540d;
										font-size: 16px;
										display: block;
										color: white;
										padding: 5px 0 0 13px;
										text-decoration: underline;
									">La plus choisie par nos clients</span>{/if}
									<p class="title_name_model same-height" data-same-height-group="short_desc_data_title">
										{l s='Gamme' d='Modules.Surmesure.Shop'} {$gamme.value|escape:'htmlall':'UTF-8'} {for $foo = 1 to $gamme.rating} &#11088; {/for}</p>
									<div class="short_desc">
										<p>“{if $gamme.avis}{$gamme.avis nofilter}{/if}”</p>
									</div>
								</div> <!-- .top-info -->
							</div> <!-- .top-info -->
							<div class="container_carpet_type">
								<div class="bloc_visus">
									<div>
										<div class="large_img">
											{assign var='color' value=$gamme.colors[0]}
											<img id="main-img-{$gamme.alias}" class="img-responsive"
												src="{$surmesure_img_url}custom/{$gamme.alias}/{$color.main_image}"
												alt="{$gamme.value}" />
										</div>
										{foreach from=$gamme.colors key=k item=details}
											<div class="block_img_thubnail block_img_thubnail_{$k}" {if $k > 0}style="display:none"
												{/if}>
												{foreach from=$details.array_images item=thb}
													<a onclick="changeImg('{$gamme.alias}','{$thb}');" href="javascript:;">
														<img src="{$surmesure_img_url}custom/{$gamme.alias}/{$thb}" width="110"
															height="110" alt="{$color.main_image}" class="img-responsive" />
													</a>
												{/foreach}
											</div>
										{/foreach}
										<div class="block_other_thubnail">
											<div class="same-height_img">
												<span
													class="title_thumb">{l s='Autres coloris' d='Modules.Surmesure.Shop'}</span>
												<ul class="other_thumbnail">
													{foreach from=$gamme.colors   key=k  item=color}
														<li>
															<a onclick="changeImg('{$gamme.alias}','{$color.main_image}','block_img_thubnail_{$k}');"
																href="javascript:;">
																<img src="{$surmesure_img_url}custom/{$gamme.alias}/{$color.main_image}"
																	width="63" height="63" alt="{$color.main_image}"
																	class="img-responsive" />
															</a>
														</li>
													{/foreach}
												</ul>
											</div>
										</div>
									</div>
								</div>
								<ul class="cd-features-list">
									{* <li class="bloc_visus">

																																		<div class="same-height" data-same-height-group="block_other_thubnail_data">
																																			<div class="large_img">
																																				{assign var='color' value=$gamme.colors[0]}
																																				<img 
																																					id="main-img-{$gamme.alias}" 
																																					class="img-responsive" 
																																					src="{$surmesure_img_url}custom/{$gamme.alias}/{$color.main_image}" 
																																					alt="{$gamme.value}" />
																																			</div>








										{foreach from=$gamme.colors key=k item=details}
																																												<div class="block_img_thubnail block_img_thubnail_{$k}" 







											{if $k > 0}style="display:none"







											{/if}>








											{foreach from=$details.array_images item=thb}
																																																					<a onclick="changeImg('{$gamme.alias}','{$thb}');" href="javascript:;">
																																																					<img 
																																																						src="{$surmesure_img_url}custom/{$gamme.alias}/{$thb}" 
																																																						width="110" height="110" 
																																																						alt="{$color.main_image}"
																																																						class="img-responsive" />
																																																					</a>








											{/foreach}
																																												</div>








										{/foreach}
																																			<div class="block_other_thubnail">
																																				<div class="same-height_img">
																																					<span class="title_thumb">







										{l s='Autres coloris' d='Modules.Surmesure.Shop'}</span>
																																					<ul class="other_thumbnail">








										{foreach from=$gamme.colors   key=k  item=color}
																																														<li>
																																															<a onclick="changeImg('{$gamme.alias}','{$color.main_image}','block_img_thubnail_{$k}');" href="javascript:;">
																																																<img 
																																																	src="{$surmesure_img_url}custom/{$gamme.alias}/{$color.main_image}" 
																																																	width="63" height="63" 
																																																	alt="{$color.main_image}"
																																																	class="img-responsive" />
																																															</a>
																																														</li>








										{/foreach}
																																					</ul>
																																				</div>
																																			</div>
																																		</div>
																																	</li> *}
										<li class="" data-same-height-group="block_feat_model_QM">
											<div class="block_feat_model_custom first">
												<span>{l s='Qualité moquette :' d='Modules.Surmesure.Shop'}</span>
												{$gamme.qualite_moquette}
											</div>
										</li>
										<li class="" data-same-height-group="block_feat_model_QC">
											<div class="block_feat_model_custom">
												<span>{l s='Qualité contour :' d='Modules.Surmesure.Shop'}</span>
												{$gamme.qualite_contour}
											</div>
										</li>
										<li class="" data-same-height-group="block_feat_model_QDM">
											<div class="block_feat_model_custom">
												<span>{l s='Qualité des matériaux :' d='Modules.Surmesure.Shop'}</span>
												{$gamme.qualite_materiaux}
											</div>
										</li>
										<li class="" data-same-height-group="block_feat_model_FSC">
											<div class="block_feat_model_custom last">
												<span>{l s='Finition sous-couche :' d='Modules.Surmesure.Shop'}</span>
												{$gamme.sous_couche}
											</div>
										</li>
										{*<li class="same-height" data-same-height-group="block_data_avantage">
										<div class="block_otherinfo_model">
											<div class="block_otherinfo_model_data same-height" data-same-height-group="block_otherinfo_model_data">
												<ul>
												{foreach from=$gamme.plus_produit item=plus}
													<li>{$plus nofilter}</li>
												{/foreach}
												</ul>
											</div>
										</div>
										</li>*}
										{*<li class="same-height text-center" data-same-height-group="block_data_dessinI">
										<a class="download_link_pdf" href="#">{l s='Télécharger le dessin' d='Modules.Surmesure.Shop'}</a>
									</li>*}
										<li class="block_date_pricectabtn" data-same-height-group="block_date_pricecta">
											{if $smarty.cookies.confidential_mode|escape:"html" != "ON"}
												<div class="block_price_cta">
													<p class="price_container">
														{*<span class="price">{$gamme.price|replace:'.':','}</span>
														<span class="taxe_unit">
															{if $espace_pro}{l s='HT' d='Modules.Surmesure.Shop'}
															{else}
																{l s='TTC' d='Modules.Surmesure.Shop'}
															{/if}
														</span>*}
													</p>
													<span class="small_text_price">
												{$gamme.surbase}
											</span>
												</div>
											{/if}
											<div class="block_price_cta">
												<form name="selectgam" method="post" action="">
													<input type="hidden" name="id_gamme" value="{$gamme.id_gamme}" />
													<input type="submit" value="{$gamme.choisi}" class="btn-primary nextBtnstep" />
												</form>
											</div>
										</li>
										{* <li class="block_date_pricectabtn" data-same-height-group="block_date_pricectabtn">
									</li> *}
									</ul>
								</div>
							</div>
						</li> <!-- .product -->
					{/foreach}

					<div id="dialog-img-popup" title="" style="display: none;">
						<img id="image_popup" src="" />
					</div>
				</ul> <!-- .cd-products-columns -->

			</section> <!-- .cd-products-comparison-table -->

		</div><!-- end #step-2 -->
	{/block}
