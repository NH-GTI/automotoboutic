{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

{if $field.values|@count > 4}
{assign var="colxsx" value="col-md-4 col-xs-4"}
{else}
{assign var="colxsx" value="col-md-4 col-xs-4"}
{/if}
			{foreach from=$field.values item=value}
				<span id="value-json-details-{$value.id|escape:'intval'}" ></span>
					{if $field.price_type == 'percent'}
						{assign var='valuePrice' value=$value.price}
					{else}
						{assign var='valuePrice' value=Tools::convertPrice($value.price, Context::getContext()->currency->id)|round:2}
					{/if}
				{if $value.set_quantity == 0 || $value.quantity > 0}
				{assign var=tags value=','|explode:$value.tags}
				<div data-hide-field="{if $value.influences_restrictions|strpos:"all" !== false}1{else}0{/if}" data-id-value="{$value.id|escape:'intval'}" class="img-select-container {$colxsx} filterTag {if $value.tags && $value.tags !=''} tagged {foreach from=$tags item=tag}{$tag|replace:' ':'-'} {/foreach}{/if} img-item-row" data-tags="{foreach from=$tags item=tag}{$tag}|{/foreach}" data-root="{$field.id_ndk_customization_field|escape:'intval'}" data-group="{$field.id_ndk_customization_field|escape:'intval'}" {if $field.dynamic_influences} data-dynamic_influences="{$field.dynamic_influences}" {/if}>
					<img class="{if $value.reference == '[:product_image]'}load_product_image{/if} {if $field.is_visual == 1}visual-effect {/if}{if $value.issvg && $value.svgcode}svg {else} jpg{/if} img-value-{$field.id_ndk_customization_field|escape:'intval'} img-responsive img-value" data-value="{$value.value|escape:'htmlall':'UTF-8'}" title="{$value.value|escape:'htmlall':'UTF-8'}"   data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-src="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{$value.id|escape:'intval'}{if $value.issvg}.svg{else}.jpg{/if}" data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}" data-dragdrop="{$field.draggable|escape:'intval'}" data-id-value="{$value.id|escape:'intval'}"
					data-resizeable="{$field.resizeable|escape:'intval'}" 
					data-rotateable="{$field.rotateable|escape:'intval'}" 
					data-quantity-available="{if $value.set_quantity >0}{$value.quantity}{else}null{/if}" data-default-value="{$value.default_value|escape:'intval'}"  
					 data-price="{if $valuePrice > 0}{$valuePrice|escape:'htmlall':'UTF-8'}{else}{$fieldPrice|escape:'htmlall':'UTF-8'}{/if}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-blend="{$field.color_effect}"
					 data-thumb="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{if !$value.issvg}thumbs/{/if}{$value.id|escape:'intval'}{if !$value.issvg}{if $value.is_texture}-texture{else}-{Configuration::get('NDK_IMAGE_SIZE')}{/if}{/if}{if $value.issvg}.svg{else}.jpg{/if}"
					 {if $field.is_mask_image}data-mask-image="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/mask/{$field.id_ndk_customization_field|escape:'intval'}.jpg"{/if}
					 />
					{if $value.issvg && $value.svgcode }
					<div class="svg-container">{$value.svgcode nofilter}</div>
					{/if}
					
				<center><i>{$value.value|escape:'htmlall':'UTF-8'}
				{if $field.show_price == 1}
					{if $valuePrice > 0} : {l s="+" mod='ndk_advanced_custom_fields'}
						{if $field.price_type == 'percent'}
							{$valuePrice}%
						{else}
							{convertPrice price=$valuePrice}
						{/if}
					{else}
						{if $fieldPrice > 0} : {l s="+" mod='ndk_advanced_custom_fields'}
							{if $field.price_type == 'percent'}
								{$fieldPrice}%
							{else}
								{convertPrice price=$fieldPrice}
							{/if}
						{/if}
					{/if}
				{/if}
				</i>
				{if $value.description !=''}
                    <div class="custom-desc-for-img">{$value.description nofilter}</div>
                    {* <div class="tooltipDescription">{$value.description nofilter}</div>
                    <span class="tooltipDescMark" style="display: none"></span> *}
				{/if}
				</center>
                <span class="select-btn-img-type">{l s='Select' mod='ndk_advanced_custom_fields'}</span>
				</div>
				{/if}
			{/foreach}
