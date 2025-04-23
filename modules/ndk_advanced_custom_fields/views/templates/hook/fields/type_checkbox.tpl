{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

<div class="form-group ndkackFieldItem field-type-{$field.type}" data-iteration="{$field_iteration}"  data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-name="{$field.name|escape:'htmlall':'UTF-8'}" data-field="{$field.id_ndk_customization_field|escape:'htmlall':'UTF-8'}" data-quantity-link="{$field.quantity_link}">
		<label class="toggler"
		{if $field.is_picto} style="background-image: url('{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/pictos/{$field.id_ndk_customization_field|escape:'intval'}.jpg');"{/if}
	>{$field.name|escape:'htmlall':'UTF-8'}
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
		{if isset($field.feature) && $field.feature > 0}
			{if isset($features) && $features}
						{foreach from=$features item=feature}
							{if $feature.id_feature == $field.feature && isset($feature.value)}
							<span class="checkbox">
								<input data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$field.name|escape:'htmlall':'UTF-8'}" id="checkbox_{$field.id_ndk_customization_field|escape:'htmlall':'UTF-8'}_0" type="checkbox" name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}][checkbox][{$value.value|escape:'intval'}]" class="{if $field.required == 1} required_field{/if} ndk-checkbox not_uniform{if $field.is_visual == 1}visual-effect {/if}" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-src="" data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}" value="{$feature.value|escape:'htmlall':'UTF-8'}" data-price="0" checked="checked" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-quantity-available="{if $value.set_quantity >0}{$value.quantity}{else}999999999{/if}" data-value-id="{$value.id|escape:'intval'}" data-hide-field="{if $value.influences_restrictions|strpos:"all" !== false}1{else}0{/if}" data-id-value="{$field.id_ndk_customization_field|escape:'intval'}-{$value.id|escape:'intval'}"
								/>
								<label for="checkbox_{$field.id_ndk_customization_field|escape:'htmlall':'UTF-8'}_0">{$feature.value|escape:'htmlall':'UTF-8'}({l s='by default'  mod='ndk_advanced_custom_fields'})</label>
								</span>
							{/if}
							
						{/foreach}
			{/if}
		
		{/if}
			{include file='../values/type_checkbox.tpl'}

			{include file='./specific_prices.tpl'}
			
		</div>	
		<input type="hidden" id="ndkcsfieldPdf_{$field.id_ndk_customization_field|escape:'intval'}" name="ndkcsfieldPdf[{$field.id_ndk_customization_field|escape:'intval'}]"/>
		
	</div>