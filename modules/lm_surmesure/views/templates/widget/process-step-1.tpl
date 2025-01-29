{block name='process_step_1_content'}
	<div class="setup-content block_custom" id="step-1">
		<div class="container">
			<form role="form" action="" method="post">
				<div>
				<h3 class="selector-msg-first-step" style="text-align: center;">Tapis sur mesure non échangeable et non remboursable</h3>
					<p class="custom-warning-message">Livraison des tapis sur mesure sous 8 à 12 jours ouvrés</p>     
					<p class="title_block" style="text-align: center;">
						{l s='Sélectionnez la marque puis le modèle de votre véhicule' d='Modules.Surmesure.Shop'}</p>
					<div class="row">
						<div class="col-md-8 offset-md-2 block_form">
							{* <div class="col-md-6 block_form"> *}
							<div class="form-group row ">
								<label
									class="col-md-5 form-control-label required">{l s='Marque de votre véhicule :' d='Modules.Surmesure.Shop'}</label>
								<div class="col-md-7">
									<select name="ma" class="form-control form-control-select"
										onchange="$(this).parents('form').eq(0).submit();">
										<option value="">{l s='Sélectionnez la marque' d='Modules.Surmesure.Shop'}</option>
										{foreach from=$featured_brands item=marque}
											<option value="{$marque.id_feature_value}"
												{if isset($marque_selected) && $marque_selected == $marque.id_feature_value}selected="selected"
												{/if}>
												{$marque.value}
											</option>
										{/foreach}
										<optgroup label="---------">
											{foreach from=$marques item=marque}
												<option value="{$marque.id_feature_value}"
													{if isset($marque_selected) && $marque_selected == $marque.id_feature_value}selected="selected"
													{/if}>
													{$marque.value}
												</option>
											{/foreach}
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group row ">
								<label
									class="col-md-5 form-control-label required">{l s='Modèle de votre véhicule :' d='Modules.Surmesure.Shop'}</label>
								<div class="col-md-7">
									<select name="mo" class="form-control form-control-select" {if empty($marque_selected)}
										disabled="disabled" {/if}>
										<option value="">{l s='Sélectionnez le modèle' d='Modules.Surmesure.Shop'}</option>
										{foreach from=$modeles item=modele}
											<option value="{$modele.id_feature_value}"
												{if isset($modele_selected) && $modele_selected == $modele.id_feature_value}selected="selected"
												{/if}>
												{$modele.value}
											</option>
										{/foreach}
									</select>
								</div>
							</div>
							<button class="btn btn-primary btn-lg pull-right nextBtnstep" type="submit">
								{l s='Etape suivante' d='Modules.Surmesure.Shop'}
							</button>
						</div>
						{*<div class="col-md-5 block_form_image pull-right">
						<img class="img-responsive" src="{$module_media_base_url}img/img_step.jpg" alt="" />
					</div>*}
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
				{if $marque_selected > 0}
					<p class="title_block">{l s='Tous les modèles' d='Modules.Surmesure.Shop'} {$marque_selected_nom}</p>
					{assign var='index' value=0}
					<div class="row">
						<div class="block_col_manu col-sm-4 col-md-3 same-height" data-same-height-group="list-model">
							<ul>
								{foreach from=$modeles item=modele}
									{*<li><a href="{$baseUrl}-{$marque_selected_nom|toUri}-{$modele.value|toUri}">{$modele.value}</a></li>*}
									<li><a
											href="{$link->getModuleLink('lm_surmesure', 'surmesure', ['ma' => $marque_selected, 'mo' => $modele.id_feature_value])}">{$modele.value}</a>
									</li>
									{math assign='index' equation='x + 1' x=$index}
									{if $index%5 == 0 && count($modeles) >= $index}
									</ul>
								</div>
								<div class="block_col_manu col-sm-4 col-md-3 same-height" data-same-height-group="list-model">
									<ul>
									{/if}
								{/foreach}
							</ul>
						</div>
					</div>
				{else}
					<p class="title_block">{l s='Marques' d='Modules.Surmesure.Shop'}</p>
					{assign var='index' value=0}
					<div class="row">
						<div class="block_col_manu col-sm-4 col-md-3 same-height" data-same-height-group="list-model">
							<ul>
								{foreach from=$marques item=marque}
									{*<li><a href="/tapis-auto-sur-mesure-{$marque.value|toUri}">{$marque.value}</a></li>*}
									<li><a
											href="{$link->getModuleLink('lm_surmesure', 'surmesure', ['ma' => $marque.id_feature_value])}">{$marque.value}</a>
									</li>
									{math assign='index' equation='x + 1' x=$index}
									{if $index%5 == 0 && count($marques) >= $index}
									</ul>
								</div>
								<div class="block_col_manu col-sm-4 col-md-3 same-height" data-same-height-group="list-model">
									<ul>
									{/if}
								{/foreach}
							</ul>
						</div>
					</div>
				{/if}


			</div>
		</div>
		{* <div style="margin-top:20px">
			<div class="container">
				{$cms_seo->content nofilter}
			</div>
		</div> *}

	</div><!-- end step-1 -->
{/block}
