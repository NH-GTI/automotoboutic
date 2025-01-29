
{if $lm_pushsurmesure.pushs}
<!-- Debut bloc sur mesure-->
<div id="block_home_surmesure" class="block_custom">
	<div class="container">
		<p class="title_block text-center">{if empty($lm_pushsurmesure.title)}{l s='Sur mesure' d='Modules.Imagepushr.Shop'}{else}{$lm_pushsurmesure.title}{/if}</p>
		<ul class="block_listing_product row">
        {foreach from=$lm_pushsurmesure.pushs item=push}
			<li class="col-md-4">
				<div class="block_content same-height" data-same-height-group="equal_block_contentSM">
					<p class="title_listing_block"><a href="{$push.cta_url}">{$push.title}</a></p>
					<div class="thumbnail">
						<img class="img-responsive" src="{$push.image_url}" alt="{$push.title|escape}" />
					</div>
				</div>
				<a href="{$push.cta_url}" class="btn-primary btn_surmesure">{$push.cta_wording}</a>
			</li>
        {/foreach}
		</ul>
	</div>
</div>
<!-- Fin bloc sur mesure-->
{/if}


