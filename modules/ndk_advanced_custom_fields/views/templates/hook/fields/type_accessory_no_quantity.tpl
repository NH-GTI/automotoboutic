{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}


<div class="form-group ndkackFieldItem field-type-{$field.type}"  data-name="{$field.name|escape:'htmlall':'UTF-8'}" data-iteration="{$field_iteration}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-field="{$field.id_ndk_customization_field|escape:'htmlall':'UTF-8'}"  data-qtty-min="{$field.quantity_min}"  data-qtty-max="{$field.quantity_max}">
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
			<!--<input data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$field.name|escape:'htmlall':'UTF-8'}" id="ndkcsfield_{$field.id_ndk_customization_field|escape:'intval'}" type="text" name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}]" value="" class="{if $field.required == 1} required_field{/if}"/>-->
			<div class="minmaxBlock">
				<p class="quantity_error_up alert-danger clear clearfix">{l s="You can't add more than " mod='ndk_advanced_custom_fields'}{$field.quantity_max} {l s='quantities' mod='ndk_advanced_custom_fields'}</p>
				<p data-name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}]" class=" alert-danger clear clearfix quantity_error_down  {if $field.quantity_min > 0}required_field{/if}" val="">{l s="You must add a minimum of " mod='ndk_advanced_custom_fields'}{$field.quantity_min} {l s='quantities' mod='ndk_advanced_custom_fields'}</p>
			</div>
			<!-- bloc tags -->
			<div class="clearfix clear row" id="main-{$field.id_ndk_customization_field|escape:'intval'}">
				<div class="clear col-xs-12 clearfix visu-tools"></div>
			</div>
			<!-- bloc tags -->
			<ul class="ndk_accessory_list accessory_no_quantity">
			{include file='../values/type_accessory_no_quantity.tpl'}
			</ul>
			{include file='./specific_prices.tpl'}
		</div>
	</div>