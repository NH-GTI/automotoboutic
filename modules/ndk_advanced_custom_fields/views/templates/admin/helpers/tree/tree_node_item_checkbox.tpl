{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<li class="tree-item{if isset($node['disabled']) && $node['disabled'] == true} tree-item-disable{/if}">
	<span class="tree-item-name{if isset($node['disabled']) && $node['disabled'] == true} tree-item-name-disable{/if}">
		<input type="checkbox" 
		{if $input_name != 'products'} name="{$input_name}[]" value="{$node['id_category']}" {else} class="categoryprodcheckbox" {/if}
		{if isset($node['disabled']) && $node['disabled'] == true} disabled="disabled"{/if} data-test="test"/>
		<i class="tree-dot"></i>
		<label class="tree-toggler"><strong>{$node['name']}</strong></label>
	</span>
	{if $input_name == 'products'}
	{assign var='products' value=NdkCf::getCategoryproductsLight($node['id_category'])}
	{if isset($products)}
	<ul class="tree">
		{foreach $products as $product}
			<li class="tree-item">
				<span class="tree-item-name">
					<input type="checkbox" name="{$input_name}[]" value="{$product['id_product']}" class="prodcheckbox"/>
					<i class="tree-dot"></i>
					<label class="tree-toggler">{$product['name']} - {if $product['reference'] !=''}{l s='REF:'} {$product['reference']}{/if} (#{$product['id_product']})</label>
				</span>
			</li>
		{/foreach}
	</ul>
	{/if}
	{/if}
</li>