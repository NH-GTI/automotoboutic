{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

<div class="form-group ndkackFieldItem field-type-{$field.type}" data-name="{$field.name|lower|escape:'htmlall':'UTF-8'}" data-admin-name="{$field.admin_name|lower|escape:'htmlall':'UTF-8'}" data-iteration="{$field_iteration}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-field="{$field.id_ndk_customization_field|escape:'htmlall':'UTF-8'}" data-quantity-link="{$field.quantity_link}" {if $field.dynamic_influences} data-dynamic_influences="{$field.dynamic_influences}" {/if} data-field-type="{$field.type}">
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
		{if $field.required == 0}
		<a href="#" style="display: none;" class="remove-img-item button btn pull-right btn-default button-small removePrice" data-group-target="{$field.id_ndk_customization_field|escape:'intval'}"><span>{l s='remove '  mod='ndk_advanced_custom_fields'}</span></a>
		{/if}
		
		<div class="img-type-container clearfix clear row {if $field.dynamic_influences} dynamic_influences {/if}" id="main-{$field.id_ndk_customization_field|escape:'intval'}">
			<div class="clear col-xs-12 clearfix visu-tools">
				{if $field.colorizesvg}
				<div class="pull-right">
					<p class="clear clearfix"><label>{l s='Images colors '  mod='ndk_advanced_custom_fields'}</label></p>
					<div class="ndk_selector">
						<ul class="colorize_svg" data-group="{$field.id_ndk_customization_field|escape:'intval'}"></ul>
					</div>
				</div>	
					
				{/if}
			</div>
			<input data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$field.name|escape:'htmlall':'UTF-8'}" id="ndkcsfield_{$field.id_ndk_customization_field|escape:'intval'}" type="hidden" name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}]" value="" class="{if $field.required == 1} required_field{/if}"/>
			
			<input type="hidden" id="ndkcsfieldPdf_{$field.id_ndk_customization_field|escape:'intval'}" name="ndkcsfieldPdf[{$field.id_ndk_customization_field|escape:'intval'}]"/>
			
			{if $field.values|@count > 4}
				{assign var="colxsx" value="col-md-3 col-xs-4"}
			{else}
				{assign var="colxsx" value="col-md-3 col-xs-4"}
			{/if}
			{include file='../values/type_image.tpl'}
			{if $field.orienteable == 1}
				{include file='./orienteable.tpl'}
			{/if}
		</div>
		
		{include file='./specific_prices.tpl'}		
	</div>
</div>
<script type="text/javascript">
	fieldColors_{$field.id_ndk_customization_field}= [];
	{if $field.colors !=''}
		{foreach from=$field.colors  item=color}
			window['fieldColors_{$field.id_ndk_customization_field}'].push('{$color}');
		{/foreach}
	{/if}
</script>


