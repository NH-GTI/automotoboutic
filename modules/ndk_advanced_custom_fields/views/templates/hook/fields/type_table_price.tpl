{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

<div class="form-group ndkackFieldItem field-type-{$field.type}" data-iteration="{$field_iteration}"  data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-name="{$field.name|escape:'htmlall':'UTF-8'}"  data-field="{$field.id_ndk_customization_field|escape:'htmlall':'UTF-8'}">
	<label class="toggler"
		{if $field.is_picto} style="background-image: url('{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/pictos/{$field.id_ndk_customization_field|escape:'intval'}.jpg');"{/if}
	><span id="resultValue_{$field.id_ndk_customization_field|escape:'intval'}"></span>{$field.name|escape:'htmlall':'UTF-8'}
		{if $field.is_visual == 1}
			<span class="layer_view visible_layer" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}"/>&nbsp;</span>
		{/if}
		{if $field.tooltip !=''}
			<div class="tooltipDescription">{$field.tooltip nofilter}</div>
				<span class="tooltipDescMark"></span>
		{/if}
	</label>
	<div class="csv_tab_render_popup">
	{*foreach $field.price_range_height as $height}
	<div class="pricetabRow">
		<span class="pricetabItem">{$height}</span>
	</div>
	{/foreach*}
	</div>
	<div class="fieldPane clearfix">
		{if $field.notice !=''}
			<div class="field_notice clearfix clear">{$field.notice nofilter}</div>
		{/if}
	
	{if $field.price_range_min_width > 0}
		<p data-name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}]" class=" alert-danger clear clearfix quantity_error_width_down  {if $field.price_range_min_width > 0}required_field{/if}" val="">{l s="You must add a minimum of " mod='ndk_advanced_custom_fields'}{$field.price_range_min_width} ({if $field.values.0.value != ''}{$field.values.0.value}{else}{l s='width' mod='ndk_advanced_custom_fields'}{/if})</p>
	{/if}
	{if $field.price_range_max_width > 0}
		<p data-name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}]" class=" alert-danger clear clearfix quantity_error_width_up  {if $field.price_range_max_width > 0}required_field{/if}" val="">{l s="You must add a maximum of " mod='ndk_advanced_custom_fields'}{$field.price_range_max_width} ({if $field.values.0.value != ''}{$field.values.0.value}{else}{l s='width' mod='ndk_advanced_custom_fields'}{/if})</p>
	{/if}
	{if $field.price_range_min_height > 0}
		<p data-name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}]" class=" alert-danger clear clearfix quantity_error_height_down  {if $field.price_range_min_height > 0}required_field{/if}" val="">{l s="You must add a minimum of " mod='ndk_advanced_custom_fields'}{$field.price_range_min_height} ({if $field.values.1.value != ''}{$field.values.1.value}{else}{l s='height' mod='ndk_advanced_custom_fields'}{/if})</p>
	{/if}
	{if $field.price_range_max_height > 0}
		<p data-name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}]" class=" alert-danger clear clearfix quantity_error_height_up  {if $field.price_range_max_height > 0}required_field{/if}" val="">{l s="You must add a maximum of " mod='ndk_advanced_custom_fields'}{$field.price_range_max_height} ({if $field.values.0.value != ''}{$field.values.1.value}{else}{l s='height' mod='ndk_advanced_custom_fields'}{/if})</p>
	{/if}
	
	<p class="dimensions_block">
		{if $field.values.0.value != ''}
			<label class="clear clearfix width_label">{$field.values.0.value}</label>
		{else}
			<label class="clear clearfix width_label">{l s='width' mod='ndk_advanced_custom_fields'}</label>
		{/if}
		
		{if $field.values.0.input_type == 'text' || $field.values.0.input_type == 'number'}
			<input id="dimension_text_width_{$field.id_ndk_customization_field|escape:'intval'}" data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$field.name|escape:'htmlall':'UTF-8'}"  placeholder="{l s='width' mod='ndk_advanced_custom_fields'}" name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}][width]" data-val="" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-price="" type="{if $field.values.0.input_type == 'number'}number{else}text{/if}" class="form-control dimension_text dimension_text_width dimension_text_{$field.id_ndk_customization_field|escape:'intval'} {if $field.required == 1} required_field{/if}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" value="{$field.price_range_min_width}" size="8" min="{$field.price_range_min_width|intval}" max="{$field.price_range_max_width}"/>
		{else}
			<select id="dimension_text_width_{$field.id_ndk_customization_field|escape:'intval'}" data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$field.name|escape:'htmlall':'UTF-8'}"  name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}][width]" data-val="" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-price="" type="text" class="form-control-ndk dimension_text dimension_text_width dimension_text_{$field.id_ndk_customization_field|escape:'intval'} {if $field.required == 1} required_field{/if}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}">
				<option value=" ">--</option>
				{foreach from=$field.price_range_width item=width}
					<option value="{$width.width}">{$width.width}</option>
				{/foreach}
			</select>
		{/if}
		
		
		{if $field.values.1.value != ''}
			<label class="clear clearfix height_label">{$field.values.1.value}</label>
		{else}
			<label class="clear clearfix height_label">{l s='height' mod='ndk_advanced_custom_fields'}</label>
		{/if}
		
		{if $field.values.1.input_type == 'text' || $field.values.1.input_type == 'number'}
			<input id="dimension_text_height_{$field.id_ndk_customization_field|escape:'intval'}" data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$field.name|escape:'htmlall':'UTF-8'}"  placeholder="{l s='height' mod='ndk_advanced_custom_fields'}" name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}][height]" data-val="" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-price="" type="{if $field.values.1.input_type == 'number'}number{else}text{/if}" class="form-control dimension_text dimension_text_height dimension_text_{$field.id_ndk_customization_field|escape:'intval'} {if $field.required == 1} required_field{/if}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" value="{$field.price_range_min_height}" min="{$field.price_range_min_height}" size="8" max="{$field.price_range_max_height}"/>
		{else}
			<select id="dimension_text_height_{$field.id_ndk_customization_field|escape:'intval'}" data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$field.name|escape:'htmlall':'UTF-8'}"   name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}][height]" data-val="" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-price="" type="text" class="form-control-ndk dimension_text dimension_text_height dimension_text_{$field.id_ndk_customization_field|escape:'intval'} {if $field.required == 1} required_field{/if}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" >
				<option value=" ">--</option>
				{foreach from=$field.price_range_height item=height name=heightLoop}
					<option value="{$height.height}">{$height.height}</option>
				{/foreach}
			</select>
		{/if}
		
		
		
	</p>
	</div>
</div>