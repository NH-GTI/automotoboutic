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
						
						<option {if $value.set_quantity == 1 && $value.quantity < 1}class="disabled_value_by_qtty"{/if} value="{$value.value|escape:'htmlall':'UTF-8'}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-src="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{$value.id|escape:'intval'}.jpg" data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}"
						 data-dragdrop="{$field.draggable|escape:'intval'}" 
						 data-resizeable="{$field.resizeable|escape:'intval'}" 
						 data-rotateable="{$field.rotateable|escape:'intval'}" 
						 {if $value.influences_parent_id>0} data-influences-parent-id="{$value.influences_parent_id|escape:'intval'}" data-influences-parent-group-id="{$value.influences_parent_id_group}"{/if}
						  data-hide-field="{if $value.influences_restrictions|strpos:"all" !== false}1{else}0{/if}" data-id-value="{$value.id|escape:'intval'}" data-default-value="{$value.default_value|escape:'intval'}" 
						 data-quantity-available="{if $value.set_quantity >0}{$value.quantity}{else}999999999{/if}"  
						 data-price="{if $valuePrice > 0}{$valuePrice|escape:'htmlall':'UTF-8'}{else}{$fieldPrice|escape:'htmlall':'UTF-8'}{/if}" {if $field.is_mask_image}data-mask-image="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/mask/{$field.id_ndk_customization_field|escape:'intval'}.jpg"{/if}>{$value.value|escape:'htmlall':'UTF-8'} 
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
						{if $value.set_quantity == 1 && $value.quantity < 1} {l s='(Out of stock)' mod='ndk_advanced_custom_fields'}{/if}
						 </option>
						
					{/foreach}
				