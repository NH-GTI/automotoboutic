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
				
				{if $value.set_quantity == 0 || $value.quantity > 0}
				{assign var=tags value=','|explode:$value.tags}
				<div class="{$colxsx} filterTag {if $value.tags && $value.tags !=''} tagged {foreach from=$tags item=tag}{$tag|replace:' ':'-'} {/foreach}{/if} audio-item-row" data-tags="{foreach from=$tags item=tag}{$tag}|{/foreach}" data-root="{$field.id_ndk_customization_field|escape:'intval'}">
					
					<span class="false_radio {if $field.is_visual == 1}visual-effect {/if} audio-value-{$field.id_ndk_customization_field|escape:'intval'} audio-responsive img-value" data-value="{$value.value|escape:'htmlall':'UTF-8'}" title="{$value.value|escape:'htmlall':'UTF-8'}"   data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-hide-field="{if $value.influences_restrictions|strpos:"all" !== false}1{else}0{/if}" data-id-value="{$value.id|escape:'intval'}"
					data-quantity-available="{if $value.set_quantity >0}{$value.quantity}{else}null{/if}" data-default-value="{$value.default_value|escape:'intval'}"  
					 data-price="{if $valuePrice > 0}{$valuePrice|escape:'htmlall':'UTF-8'}{else}{$fieldPrice|escape:'htmlall':'UTF-8'}{/if}" 
					 data-id="{$field.target|escape:'htmlall':'UTF-8'}" 
					 data-view="{$field.target_child|escape:'htmlall':'UTF-8'}"></span>
					<audio controls>
					 <source src="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{$value.id|escape:'intval'}.mp3" type="audio/mpeg">
					 {l s='Your browser does not support the audio element.' mod='ndk_advanced_custom_fields'}
					 </audio>
					
					
				<center><i>{$value.value|escape:'htmlall':'UTF-8'}
				{if $field.show_price == 1}
					{if $valuePrice > 0 && $field.show_price==1} : {l s="+" mod='ndk_advanced_custom_fields'}
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
				</i></center>
				</div>
				{/if}
			{/foreach}
		