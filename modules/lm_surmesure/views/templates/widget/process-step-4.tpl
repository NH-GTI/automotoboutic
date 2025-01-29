{block name='process_step_4_content'}
	<div class="setup-content block_custom" id="step-4">
		<div class="container">{l s='' d='Modules.Surmesure.Shop'}
			<p class="title_block">{l s='Choisissez votre configuration de tapis' d='Modules.Surmesure.Shop'}</p>
			<div class="block_choice_recap">
				<div class="row">
					<div class="col-md-8 form-choose-config-color">
						<div class="block_choice">
							<table class="table">
								<tbody>
									{assign var='index' value=0}
									<tr class="row">
										{foreach from=$configurations item=configuration name=configurationsLoop}
											<td class="col-md-6">
												<label
													for="id_conf-id_product-{$configuration.id_conf}-{$configuration.product->id}"
													class="block_choice_container id_conf-id_product-{$configuration.id_conf}-{$configuration.product->id}{if !empty($configuration_selected) && !empty($product_selected) && $configuration_selected == $configuration.id_conf && $product_selected == $configuration.product->id } active{elseif empty($confProductChecked)} active{/if}">
													<div class="block_choice_top same-height"
														data-same-height-group="block_choice_top_data">
														<div class="row">
															<div class="choice_button col-xs-1 col-sm-1 col-md-1">
																<span class="custom-radio">
																	<input type="radio"
																		id="id_conf-id_product-{$configuration.id_conf}-{$configuration.product->id}"
																		name="id_conf-id_product"
																		value="{$configuration.id_conf}-{$configuration.product->id}"
																		data-id_conf="{$configuration.id_conf}"
																		data-id_product="{$configuration.product->id}"
																		data-id_product="{$configuration.product->id}"
																		data-image="{$surmesure_img_url}custom/configurations/{$configuration.image}"
																		data-desc_target="#surmesure-conf_description-{$configuration.id_conf}-{$configuration.product->id}" {if !empty($configuration_selected) && !empty($product_selected)
																																				&& $configuration_selected == $configuration.id_conf
																																				&& $product_selected == $configuration.product->id }checked="checked"
																		{assign var='confProductChecked' value=true}
																	{elseif empty($confProductChecked)}checked="checked"
																	{assign var='confProductChecked' value=true} {/if} />
																<span></span>
															</span>
														</div>
														<div class="col-xs-3 col-sm-3 col-md-3">
															<div class="img_choice">
																<img class="img-responsive"
																	src="{$surmesure_img_url}custom/configurations/{$configuration.image}"
																	alt="{$configuration.value}" />
															</div>
														</div>
														<div class="col-xs-8 col-sm-8 col-md-8 desc_choice">
															<div id="surmesure-conf_description-{$configuration.id_conf}-{$configuration.product->id}"
																class="details_choice_text">
																{$configuration.description nofilter}
															</div>
														</div>
													</div>
												</div>
												<div class="block_choice_price text-center">
													<div class="price_container"
														{if $smarty.cookies.confidential_mode|escape:"html" == "ON"}
														style="display:none" {/if}>

														{if $espace_pro}
															{assign var='tax' value=0}
														{else}
															{assign var='tax' value=1}
														{/if}
														<span
															class="price">{$configuration.product->getPrice($tax, $smarty.const.NULL, 2)|@html_entity_decode|replace:'.':','}</span>
														<span class="taxe_unit">
															{if $espace_pro}
																{l s='HT' d='Modules.Surmesure.Shop'}
															{else}
																{l s='TTC' d='Modules.Surmesure.Shop'}
															{/if}
														</span>
													</div>
												</div>
											</label>
										</td>
										{math assign='index' equation='x + 1' x=$index}
										{if $index%2 == 0 && count($configurations) >= $index}
										</tr>
										<tr class="row">
										{/if}
									{/foreach}
								</tr>

							</tbody>
						</table>

						<div class="block_choice_color_visus_contain">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="block_choice_color_visus same-height"
										data-same-height-group="block_choice_color">

										{foreach from=$colors item='color' key='j'}
											<div id="imgcolors-{$color.alias}" class="block_color_image imgcontainer"
												style="display: none;">
												<div class="block_visus">
													<img class="img-responsive" id="main-img-{$j}"
														src="{$surmesure_img_url}custom/{$gamme_selected_alias}/{$color.images[0]|replace:'.png':'-hd.png'}"
														alt="{$color.alias}" />
												</div>
												<ul class="other_thumbnail clearfix">
													{foreach $color.images item='img' key='k'}
														<li>
															<a href="{$surmesure_img_url}custom/{$gamme_selected_alias}/{$img|replace:'.png':'-hd.png'}"
																id="link-{$j}-{$k}" class="img-fancybox"
																onmouseover="changeImg({$j}, {$k},'{$color.alias}')">
																<img class="img-responsive" id="img-{$j}-{$k}"
																	src="{$surmesure_img_url}custom/{$gamme_selected_alias}/{$img}"
																	alt="{$color.alias}" width="50" />
															</a>
														</li>
													{/foreach}
												</ul>
											</div>
										{/foreach}
										<p class="img_infos">NB: Les photos sont non contractuelles, les tapis de sol
											correspondront aux tapis d&rsquo;origine.</p>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6">
									<div class="block_choice_color same-height"
										data-same-height-group="block_choice_color">
										<p class="title">{l s='Gamme' d='Modules.Surmesure.Shop'} {$gamme_selected_nom}
										</p>
										<p class="subtitle">
											{l s='Sélectionnez la couleur de votre choix' d='Modules.Surmesure.Shop'}
										</p>

										{assign var='index' value=0}
										{foreach from=$colors item='color'}
											<label for="radio-{$color.alias}"
												class="{if !empty($color_selected) && $color_selected == $color.id_couleur } active{elseif empty($colorChecked)} active{/if}{if $color.alias == 'carat-bleu' || $color.alias == 'carat-rouge'} twolines{/if}"
												id="link-{$index}" onclick="loadColor('{$color.alias}',{$index})">
												<span class="custom-radio">
													<input type="radio" name="color" id="radio-{$color.alias}"
														value="{$color.id_couleur}" data-color_alias="{$color.alias}"
														data-background="url('{$surmesure_img_url}colors/{$color.image}"
														data-color_name="{$color.value}"
														{if !empty($color_selected) && $color_selected == $color.id_couleur }checked="checked"
															{assign var='colorChecked' value=true}
														{elseif empty($colorChecked)}checked="checked"
														{assign var='colorChecked' value=true} {/if} />
													<span></span>
												</span>
												<span class="color_palette"
													style="background: url('{$surmesure_img_url}colors/{$color.image}') no-repeat 0 3px"></span>
												<span class="color_name">{$color.value}</span>
												{math assign='index' equation='x + 1' x=$index}
											</label>
										{/foreach}

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<form class="block_choice" action="" method="post">
						<div class="block_recap_config">
							<p class="title">{l s='Récapitulatif' d='Modules.Surmesure.Shop'}</p>
							<ul>
								<li><span>{l s='Marque :' d='Modules.Surmesure.Shop'}</span>
									{$marque_selected_nom|@ucfirst}</li>
								<li><span>{l s='Modèle :' d='Modules.Surmesure.Shop'}</span> {$modele_selected_nom}</li>
								<li><span>{l s='Finition :' d='Modules.Surmesure.Shop'}</span>
									{l s='Gamme' d='Modules.Surmesure.Shop'} {$gamme_selected_nom}</li>
								<li>
									<span>{l s='Configuration :' d='Modules.Surmesure.Shop'}</span>
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
									<span>{l s='Coloris :' d='Modules.Surmesure.Shop'}</span>
									<span class="color_palette" style=""></span>
									<span class="color_palette_text"></span>
								</li>
							</ul>
							<div class="price_cta text-center">
								<div class="price_container"
									{if $smarty.cookies.confidential_mode|escape:"html" == "ON"} style="display:none"
									{/if}>
									<span class="price"></span>
									<span class="taxe_unit"></span>
								</div>

								<input type="hidden" name="id_conf" id="surmesure-id_conf" value="" />
								<input type="hidden" name="id_product" id="surmesure-id_product" value="" />
								<input type="hidden" name="color" id="surmesure-color" value="" />
								<input type="hidden" name="price" id="surmesure-price" value="" />
								<input type="submit" value="{l s='Valider mon choix' d='Modules.Surmesure.Shop'}"
									class="btn-primary" />
							</div>
						</div>

						<div class="rea_bloc">
							<img class="img-responsive" src="{$module_media_base_url}img/rea_img.png" alt="" />
							<span>{l s='Expédié sous 5 jours ouvrés' d='Modules.Surmesure.Shop'}</span>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="separator"></div>
		<div class="block_feature_block">
			<div class="rte">
				<div class="data-sheet">
					<p><span class="name">{l s='Qualité moquettte :' d='Modules.Surmesure.Shop'}</span><span
							class="value"> {$gamme.qualite_moquette}</span></p>
					<p><span class="name">{l s='Qualité contour :' d='Modules.Surmesure.Shop'}</span><span
							class="value"> {$gamme.qualite_contour}</span></p>
					<p><span class="name">{l s='Qualité des matériaux :' d='Modules.Surmesure.Shop'}</span><span
							class="value"> {$gamme.qualite_materiaux}</span></p>
					<p><span class="name">{l s='Finition sous-couche :' d='Modules.Surmesure.Shop'}</span><span
							class="value"> {$gamme.sous_couche}</span></p>
					<p><span class="name">{l s='Coloris disponibles' d='Modules.Surmesure.Shop'}</span><span
							class="value"> {$gamme.coloris}</span></p>
				</div>
				<ul>
					{foreach from=$gamme.plus_produit item=plus}
						<li>{$plus nofilter}</li>
					{/foreach}
				</ul>

				{if $gamme_details.id_gamme == 3262} {*basique*}

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

				{elseif $gamme_details.id_gamme == 3265} {*premium*}

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

				{elseif $gamme_details.id_gamme == 3266} {*grandtourisme*}

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

				{elseif $gamme_details.id_gamme == 3264} {*elite*}

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

				{elseif $gamme_details.id_gamme == 50171} {*carat*}

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

				{/if}
			</div>
		</div>
	</div><!-- end .container -->
</div><!-- end #step-3 -->
{/block}
