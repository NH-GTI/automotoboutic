{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2017 Hendrik Masson
 *  @license   Tous droits réservés
*}


	{if $field}<input data-id-product="{$id_product}" type="hidden" class="ndkcfFromPrice" value="{l s='From' mod='ndk_advanced_custom_fields'} {convertPrice price=$price}"/>
	<span class="fromPrice" itemprop="price">{l s='From' mod='ndk_advanced_custom_fields'} {convertPrice price=$price}</span>
	{/if}
	{if $is_required}
		<input data-id-product="{$id_product}" data-link="{Context::getContext()->link->getProductLink($id_product)}" type="hidden" class="hideThisAddToCart" value="{l s='Customize' mod='ndk_advanced_custom_fields'}"/>
	{/if}
