{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}


<div class="form-group ndkackFieldItem field-type-{$field.type}" data-iteration="{$field_iteration}"  data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-name="{$field.name|escape:'htmlall':'UTF-8'}" data-field="{$field.id_ndk_customization_field|escape:'htmlall':'UTF-8'}">

	<label class="toggler"
		{if $field.is_picto} style="background-image: url('{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/pictos/{$field.id_ndk_customization_field|escape:'intval'}.jpg');"{/if}
	>{$field.name|escape:'htmlall':'UTF-8'} 
	{if $field.show_price == 1}
		{if $field.price_per_caracter > 0}{l s='cost : ' mod='ndk_advanced_custom_fields'}{convertPrice price=$field.price_per_caracter} {l s='per caracter' mod='ndk_advanced_custom_fields'}
		{elseif $fieldPrice > 0}{l s='cost : ' mod='ndk_advanced_custom_fields'}{convertPrice price=$fieldPrice}{/if}
	{/if}
	{if $field.is_visual == 1}
		<span class="layer_view visible_layer" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}"/>&nbsp;</span>
	{/if}
	{if $field.tooltip !=''}
		<div class="tooltipDescription">{$field.tooltip nofilter}</div>
			<span class="tooltipDescMark"></span>
	{/if}
	</label>
	<div class="fieldPane clearfix">
		{if $field.notice !=''}
			<div class="field_notice clearfix clear">{$field.notice nofilter}</div>
		{/if}
		
		
		<div class="clearfix clear bordered">
			<input id="ndkcsfield_{$field.id_ndk_customization_field|escape:'intval'}" {if $field.maxlength > 0}maxlength="{$field.maxlength}" {/if} data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$field.name|escape:'htmlall':'UTF-8'}" type="text" name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}]" class="{if $field.required == 1} required_field{/if} form-control simpleText {if $field.is_visual == 1}visual-text-custom-font{/if}"  data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}" data-price="{$fieldPrice|escape:'htmlall':'UTF-8'}" data-ppcprice="{$field.price_per_caracter|escape:'htmlall':'UTF-8'}" placeholder="{$field.name|escape:'htmlall':'UTF-8'} {if $fieldPrice > 0}{l s='cost : ' mod='ndk_advanced_custom_fields'}{convertPrice price=$fieldPrice}{/if}"  data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-dragdrop="{$field.draggable|escape:'intval'}" 
			data-resizeable="{$field.resizeable|escape:'intval'}" 
			data-rotateable="{$field.rotateable|escape:'intval'}"
			data-path="{$field.svg_path|escape:'UTF-8'}" data-blend="normal"/>
			<p class="clearfix clear previewText">{l s='preview' mod='ndk_advanced_custom_fields'}</p>
			<div class="custom-font-rendering">{l s='Start typing to see preview' mod='ndk_advanced_custom_fields'}</div>
			<p class="clearfix clear">&nbsp;</p>
			{if $field.is_visual == 1}<span class="btn submitCSText">{l s='Apply' mod='ndk_advanced_custom_fields'}</span>{/if}
		</div>
	</div>
</div>

<input type="hidden" id="ndkcsfieldPdf_{$field.id_ndk_customization_field|escape:'intval'}" name="ndkcsfieldPdf[{$field.id_ndk_customization_field|escape:'intval'}]"/>


<script>
	fieldLetters_{$field.id_ndk_customization_field}= [];
</script>

	{if $field.values && $field.values|@count > 0}
	<style>
		{foreach from=$field.values  item=value}
			{assign var="letters" value="|"|explode:$value.value}
				{foreach from=$letters item=letter}
					span.customFont_{$field.id_ndk_customization_field}_letter_{$letter}{
						content:url("{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{$value.id}{if $value.issvg}.svg{else}.jpg{/if}");
					}
				{/foreach}
		{/foreach}
	</style>
	{/if}
	
	

