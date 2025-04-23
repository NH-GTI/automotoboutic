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
								<div class="{$colxsx} filterTag {if $value.tags && $value.tags !=''} tagged {foreach from=$tags item=tag}{$tag|replace:' ':'-'} {/foreach}{/if} img-item-row" data-tags="{foreach from=$tags item=tag}{$tag}|{/foreach}" data-root="{$field.id_ndk_customization_field|escape:'intval'}">
									<img class="{if $field.is_visual == 1}visual-effect {/if}{if $value.issvg}svg {else} jpg{/if} img-value-{$field.id_ndk_customization_field|escape:'intval'} img-responsive img-value" data-value="{$value.value|escape:'htmlall':'UTF-8'}" title="{$value.value|escape:'htmlall':'UTF-8'}"  data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-src="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{$value.id|escape:'intval'}{if $value.issvg}.svg{else}.jpg{/if}" data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}" data-dragdrop="{$field.draggable|escape:'intval'}" data-hide-field="{if $value.influences_restrictions|strpos:"all" !== false}1{else}0{/if}" data-id-value="{$value.id|escape:'intval'}"
									data-resizeable="{$field.resizeable|escape:'intval'}" 
									data-rotateable="{$field.rotateable|escape:'intval'}" 
									data-quantity-available="{if $value.set_quantity >0}{$value.quantity}{else}null{/if}"  
									 data-price="0" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-default-value="{$value.default_value|escape:'intval'}"
									  data-thumb="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{if !$value.issvg}thumbs/{/if}{$value.id|escape:'intval'}{if !$value.issvg}{if $value.is_texture}-texture{else}-{Configuration::get('NDK_IMAGE_SIZE')}{/if}{/if}{if $value.issvg}.svg{else}.jpg{/if}"  data-blend="{$field.color_effect}"/>
									{if $value.issvg}
									<div class="svg-container">{$value.svgcode nofilter}</div>
									{/if}
									
								<center><i>{$value.value|escape:'htmlall':'UTF-8'}</i>
								{if $value.description !=''}
										<div class="tooltipDescription">{$value.description nofilter}</div>
											<span class="tooltipDescMark"></span>
									{/if}
								</center>
								</div>
								{/if}
							{/foreach}