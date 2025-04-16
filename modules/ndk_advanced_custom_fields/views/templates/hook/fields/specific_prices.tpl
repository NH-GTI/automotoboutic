{if $field.show_price == 1}
{assign var=quantity_discounts_all value=NdkCfSpecificPrice::getSpecificPrices($field.id_ndk_customization_field, 0)}

{if (isset($quantity_discounts_all))  && $quantity_discounts_all.0.reduction > 0}
	<p class="clear clearfix toggleQuantityDiscountBlock">{l s='Volume discounts ' mod='ndk_advanced_custom_fields'}</p>
	<div class="specificPriceBlock" style="display: none;">
		{foreach from=$field.values item=value}
			{assign var=quantity_discounts value=NdkCfSpecificPrice::getSpecificPrices($field.id_ndk_customization_field, $value.id, 0, true, Tools::getValue('id_product'))}
			{assign var='valuePrice' value=Tools::convertPrice($value.price, Context::getContext()->currency->id)|round:2}
			
				{if (isset($quantity_discounts) && $quantity_discounts|@count > 0) && $value.price > 0 && $quantity_discounts.0.reduction > 0}
					<!-- quantity discount -->
					
					<section class="page-product-box-ndk ">
						<p class="clear clearfix toggleQuantityDiscount">{l s='Volume discounts for' mod='ndk_advanced_custom_fields'} {$value.value}</p>
						<div class="quantityDiscount">
							<table class="std table-product-discounts">
								<thead>
									<tr>
										<th>{l s='Quantity'}</th>
										<th>{l s='Discount' mod='ndk_advanced_custom_fields'}</th>
										<th>{l s='You Save' mod='ndk_advanced_custom_fields'}</th>
									</tr>
								</thead>
								<tbody>
								{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
									{if $quantity_discount.reduction >= 0 || $quantity_discount.reduction_type == 'amount'}
										{$realDiscountPrice=$valuePrice|floatval-$quantity_discount.reduction|floatval}
									{else}
										{$realDiscountPrice=$valuePrice|floatval-($valuePrice*$quantity_discount.reduction)|floatval}
									{/if}
									<tr id="quantityDiscount_{$quantity_discount.id_ndk_customization_field_value}" class="quantityDiscount_{$quantity_discount.id_ndk_customization_field_value}" data-real-discount-value="{convertPrice price = $realDiscountPrice}" data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.reduction|floatval}" data-discount-quantity="{$quantity_discount.from_quantity|intval}">
										<td>
											{$quantity_discount.from_quantity|intval}
										</td>
										<td>
											{if $quantity_discount.reduction >= 0 && $quantity_discount.reduction_type == 'amount'}
													{convertPrice price=$quantity_discount.reduction}
												
											{else}
												{$quantity_discount.reduction|floatval}%
												
											{/if}
										</td>
										<td>
											{if $quantity_discount.reduction >= 0 && $quantity_discount.reduction_type == 'amount'}
												{$discountPrice=$valuePrice|floatval-$quantity_discount.reduction|floatval}
											{else}
												{$discountPrice=$valuePrice|floatval-($valuePrice*($quantity_discount.reduction/100))|floatval}
											{/if}
											{$discountPrice=$discountPrice * $quantity_discount.from_quantity}
											{$qtyProductPrice=$valuePrice|floatval * $quantity_discount.from_quantity}
											{convertPrice price=$qtyProductPrice - $discountPrice}
										</td>
									</tr>
								{/foreach}
								</tbody>
							</table>
						</div>
					</section>
				{/if}
		{/foreach}
	</div>
{/if}
{/if}
{assign var=quantity_discounts_named value=NdkCfSpecificPrice::getSpecificPricesNamed($field.id_ndk_customization_field, 0, 0, Tools::getValue('id_product'))}
<script type="text/javascript">
	ndkSpecificPrices[{$field.id_ndk_customization_field}] = {$quantity_discounts_named|@json_encode nofilter};
</script>
