
{block name='process_step_2_content'}
    <script type="text/javascript">
    var surmesure_img_url = "{$surmesure_img_url}custom/";
	</script>
	
	<div class="setup-content block_custom block_model_step2" id="step-2">
		<div class="seotext"> <!-- .seotext-top -->
{*			<p>
				{$seoTexts[0]['value'] nofilter} <!-- Notice: Display html tags within a smarty variable -->
				<a href="" id="seo-show-more">...Lire plus</a>
			</p>
*}
		</div>
		<div class="container">
			<p class="title_block">{l s='Sélectionnez votre modèle dans la liste ci-dessous' d='Modules.Surmesure.Shop'}</p>
        </div> 
		<section class="cd-products-comparison-table">
			<div class="cd-products-table">
				{assign var="counter" value=0 }
				{foreach from=$modeles item=mod key=key}
					<form name="selectmod" method="post" action="">
						<h3>{$mod.title}</h3>
						<p>{$mod.caracs}</p>
						<input type="hidden" name="id_model" value="{$mod.id_feature_value}" />
						<input type="hidden" name="name_model" value="{$mod.value}" />
						<img class="custom_img_model" src="{$mod.img}" alt="{$mod.value}" {if $mod.img == "none"}
							style="display:none"
						{/if}>
						<input type="submit" value="Choisir" class="btn-primary nextBtnstep" />
					</form>
					{assign var="counter" value=$counter+1 }
				{/foreach}
				{for $foo=1 to 4 - ($counter % 4)}
					<div></div>
				{/for}
			</div> <!-- .cd-products-table -->
			<div class="seotext">
				<h2>Tapis {$marque_selected_nom|@ucfirst} {$family_selected_nom|@ucfirst} gammes</h2>
				<p>
					{$seoTexts[1]['value'] nofilter}
				</p>
			</div>
			<div class="seotext">
				<h2>Tapis {$marque_selected_nom|@ucfirst} {$family_selected_nom|@ucfirst} sur mesure</h2>
				<p>
					{$seoTexts[2]['value'] nofilter}
				</p>
			</div>
		</section> <!-- .cd-products-comparison-table -->
	</div><!-- end #step-2 -->
{/block}
