{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}


<div class="form-group ndkackFieldItem field-type-{$field.type}" data-iteration="{$field_iteration}"  data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}"  data-name="{$field.name|escape:'htmlall':'UTF-8'}" data-field="{$field.id_ndk_customization_field|escape:'htmlall':'UTF-8'}">
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
			<div class="clearfix" id="main-{$field.id_ndk_customization_field|escape:'intval'}">
				{if $field.notice !=''}
					<div class="field_notice clearfix clear">{$field.notice nofilter}</div>
				{/if}
				<input data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$field.name|escape:'htmlall':'UTF-8'}" id="ndkcsfield_{$field.id_ndk_customization_field|escape:'intval'}" type="text" name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}]" value="" class="{if $field.required == 1} required_field{/if} ndk-colorpicker" data-hex="true"/>
				
				<input type="hidden" id="ndkcsfieldPdf_{$field.id_ndk_customization_field|escape:'intval'}" name="ndkcsfieldPdf[{$field.id_ndk_customization_field|escape:'intval'}]"/>
				
				
				
				<li class="color-ndk {if $field.is_visual == 1}visual-effect {/if} hidden" data-value=" " title=" "  
					data-src="0" data-group="{$field.id_ndk_customization_field|escape:'intval'}"  data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}" 
					data-dragdrop="{$field.draggable|escape:'intval'}" 
					data-resizeable="{$field.resizeable|escape:'intval'}" 
					data-rotateable="{$field.rotateable|escape:'intval'}" 
					data-id-value="0" data-default-value="0"
					data-color="#FFFFFF" 
					data-id="" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-blend="{$field.color_effect}" 
					{if $field.is_mask_image}data-mask-image="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/mask/{$field.id_ndk_customization_field|escape:'intval'}.jpg"{/if}>
					<span style="background:">&nbsp;</span>
					</li>
				{if $field.orienteable == 1}
					{include file='./orienteable.tpl'}
				{/if}
			</div>
			{include file='./specific_prices.tpl'}
		</div>
	</div>