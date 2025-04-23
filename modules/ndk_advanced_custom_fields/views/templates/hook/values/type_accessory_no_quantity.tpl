{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}



			{foreach from=$field.values item=value}
				{assign var=tags value=','|explode:$value.tags}
				{if $field.price_type == 'percent'}
					{assign var='valuePrice' value=$value.price}
				{else}
					{assign var='valuePrice' value=Tools::convertPrice($value.price, Context::getContext()->currency->id)|round:2}
				{/if}
				{if $value.set_quantity == 0 || $value.quantity > 0}
				<li class="col-xs-6 clearfix accessory-ndk accessory-ndk-no-quantity {if $field.is_visual == 1}visual-effect {/if} filterTag {if $value.tags && $value.tags !=''} tagged {foreach from=$tags item=tag}{$tag|replace:' ':'-'} {/foreach}{/if}" data-tags="{foreach from=$tags item=tag}{$tag}|{/foreach}"  data-value="{$value.value|escape:'htmlall':'UTF-8'}" title="{$value.value|escape:'htmlall':'UTF-8'}"  data-src="{if $value.is_image}{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/thumbs/{$value.id|escape:'intval'}-{Configuration::get('NDK_IMAGE_LARGE_SIZE')}.jpg{else}0{/if}" data-group="{$field.id_ndk_customization_field|escape:'intval'}"  data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}" 
				  data-dragdrop="{$field.draggable|escape:'intval'}" 
				  data-resizeable="{$field.resizeable|escape:'intval'}" 
				  data-rotateable="{$field.rotateable|escape:'intval'}" 
				  data-price="{if $valuePrice > 0}{$valuePrice|escape:'htmlall':'UTF-8'}{else}{$fieldPrice|escape:'htmlall':'UTF-8'}{/if}"
				  data-id="{$field.target|escape:'htmlall':'UTF-8'}" 
				  data-id-value="{$value.id|escape:'htmlall':'UTF-8'}" 
				  data-view="{$field.target_child|escape:'htmlall':'UTF-8'}">
				  <div class="accessory_img_block clear clearfix">
				  	<img class="img-responsive" src="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/thumbs/{$value.id|escape:'intval'}-{Configuration::get('NDK_IMAGE_SIZE')}.jpg"/>
				  	{if $value.description!= ''}
				  	<a class="fancybox accessory-more" href="#accessory-popup-{$value.id|escape:'intval'}"></a>
				  	{/if}
				  </div>
				 <div style="display:none">
					 <div id="accessory-popup-{$value.id|escape:'intval'}" class="accessory-popup-ndk">
					 	{if $value.is_image}
						 	<div class="col-md-6 ndk-img-block">
						 		<img data-target-value="{$value.id|escape:'intval'}" class="img-responsive set_one_quantity_img" src="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/thumbs/{$value.id|escape:'intval'}-{Configuration::get('NDK_IMAGE_LARGE_SIZE')}.jpg"/>
						 	</div>
					 	{/if}
					 	<div class="col-sm-6 ndk-infos-block">
					 		<p class="title_block">{$value.value|escape:'htmlall':'UTF-8'}</p>
					 		<div class="ndk-accessory-desc">{$value.description nofilter}</div>
					 		{if $field.show_price == 1}
						 		<div class="price">
						 			{if $valuePrice > 0}  
						 					{if $field.price_type == 'percent'}
						 						+{$valuePrice}%
						 					{else}
						 						{convertPrice price=$valuePrice}
						 					{/if}
						 				{else}
						 					{if $fieldPrice > 0} : 
						 						{if $field.price_type == 'percent'}
						 							+{$fieldPrice}%
						 						{else}
						 							{convertPrice price=$fieldPrice}
						 						{/if}
						 					{/if}
						 				{/if}
						 		</div>
						 	{/if}
					 	</div>
					 </div>
				</div>
				 <div class="clear clearfix accessory-infos">
				 	<b>{$value.value|escape:'htmlall':'UTF-8'}</b>
				 	
				 	<p class="ndk-accessory-quantity-block">
				 	{assign var='defaultValue' value=0}
				 		{if $value.step_quantity !=''}
				 			{assign var="steps" value=";"|explode:$value.step_quantity}
				 		{foreach from=$steps item=step}
				 			{if $step|strstr:"*"}
				 					{assign var="defaultValue" value=$step|replace:"*":""}
				 			{/if}
				 		{/foreach}
				 		{/if}
				 		
				 		<input type="text" name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}][quantity][{$value.value|escape:'intval'}]" {if $value.set_quantity == 1}data-qtty-available="{$value.quantity|escape:'intval'}" {/if}  data-qtty-max="{$value.quantity_max|escape:'intval'}"  data-qtty-min="{$value.quantity_min|escape:'intval'}" {if $value.quantity_max > 0}max="{$value.quantity_max|escape:'intval'}"{/if}  min="{$value.quantity_min|escape:'intval'}"  type="text" class="ndk-accessory-quantity price_overrided" id="ndk-accessory-quantity-{$value.id|escape:'intval'}" 
				 		value="{if $defaultValue > 0 && $defaultValue > $value.quantity_min}{$defaultValue}{else}{$value.quantity_min|escape:'intval'}{/if}"
				 		data-default-value="{if $defaultValue > 0 && $defaultValue > $value.quantity_min}{$defaultValue}{else}{$value.quantity_min|escape:'intval'}{/if}"  
				 		data-step_quantity="{$value.step_quantity|escape:'htmlall'|replace:'*':''}" 
				 		data-price="{if $valuePrice > 0}{$valuePrice|escape:'htmlall':'UTF-8'}{else}{$fieldPrice|escape:'htmlall':'UTF-8'}{/if}" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-hide-field="{if $value.influences_restrictions|strpos:"all" !== false}1{else}0{/if}" data-id-value="{$value.id|escape:'intval'}"  data-value="{$value.value|escape:'htmlall':'UTF-8'}" 
				 		data-value-id="{$field.id_ndk_customization_field|escape:'intval'}-{$value.id|escape:'intval'}" data-step_quantity="{$value.step_quantity|escape:'intval'}"/>
				 	</p>
				 	{if $field.show_price == 1}
					 	<div class="price">
					 		{if $valuePrice > 0}  
					 			{if $field.price_type == 'percent'}
					 				+{$valuePrice}%
					 			{else}
					 				{convertPrice price=$valuePrice}
					 			{/if}
					 		{else}
					 			{if $fieldPrice > 0} : 
					 				{if $field.price_type == 'percent'}
					 					+{$fieldPrice}%
					 				{else}
					 					{convertPrice price=$fieldPrice}
					 				{/if}
					 			{/if}
					 		{/if}
					 	</div>
					 {/if}
				 </div>
				 
				</li>
				{/if}
			{/foreach}
			