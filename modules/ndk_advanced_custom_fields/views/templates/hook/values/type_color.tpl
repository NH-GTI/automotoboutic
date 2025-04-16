{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

				{foreach from=$field.values item=value}
					{if $field.price_type == 'percent'}
						{assign var='valuePrice' value=$value.price}
					{else}
						{assign var='valuePrice' value=Tools::convertPrice($value.price, Context::getContext()->currency->id)|round:2}
					{/if}
					{assign var=tags value=','|explode:$value.tags}
					{if $value.set_quantity == 0 || $value.quantity > 0}
					<li class="color-ndk {if $field.is_visual == 1}visual-effect {/if} filterTag {if $value.tags && $value.tags !=''} tagged {foreach from=$tags item=tag}{$tag|replace:' ':'-'} {/foreach}{/if}" data-value="{$value.value|escape:'htmlall':'UTF-8'}" title="{$value.value|escape:'htmlall':'UTF-8'}"  
					data-tags="{foreach from=$tags item=tag}{$tag}|{/foreach}"
					data-src="{if $value.is_image}{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{$value.id|escape:'intval'}.jpg{else}0{/if}" data-group="{$field.id_ndk_customization_field|escape:'intval'}"  data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}" 
					data-dragdrop="{$field.draggable|escape:'intval'}" 
					data-resizeable="{$field.resizeable|escape:'intval'}" 
					data-rotateable="{$field.rotateable|escape:'intval'}" 
					 data-hide-field="{if $value.influences_restrictions|strpos:"all" !== false}1{else}0{/if}" data-id-value="{$value.id|escape:'intval'}" data-default-value="{$value.default_value|escape:'intval'}"
					data-quantity-available="{if $value.set_quantity >0}{$value.quantity}{else}999999999{/if}"  
					data-color="{if $value.is_texture}url('{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{$value.id|escape:'intval'}-texture.jpg'){else}{$value.color|escape:'htmlall':'UTF-8'}{/if} " 
					 data-price="{if $valuePrice > 0}{$valuePrice|escape:'htmlall':'UTF-8'}{else}{$fieldPrice|escape:'htmlall':'UTF-8'}{/if}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-blend="{$field.color_effect}" {if $field.is_mask_image}data-mask-image="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/mask/{$field.id_ndk_customization_field|escape:'intval'}.jpg"{/if}>
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
								<div class="tooltipDescription">{$value.description nofilter}</div>
									<span class="tooltipDescMark"></span>
							{/if}
						</center>
						<span style="background:{if $value.is_texture}url('{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/thumbs/{$value.id|escape:'intval'}-texture.jpg'){/if} {$value.color|escape:'htmlall':'UTF-8'}">&nbsp;</span>
					</li>
					{/if}
				{/foreach}
				