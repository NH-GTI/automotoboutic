<table class="product" width="100%" cellpadding="4" cellspacing="0">

	<thead>
		<tr>
			<th class="product header small" width="75%">{l s='Product' d='Shop.Pdf' pdf='true'}</th>
			<th class="product header small" width="15%">{l s='Reference' d='Shop.Pdf' pdf='true'}</th>
			<th class="product header small" width="10%">{l s='Qty' d='Shop.Pdf' pdf='true'}</th>
		</tr>
	</thead>

	<tbody>
		<!-- PRODUCTS -->
		{foreach $products_list as $product}
			{cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
			
			{if !empty($product.gamme) }
				<tr class="product {$bgcolor_class}">

					<td class="product left">
						{if $display_product_images}
							<table width="100%">
								<tr>
									<td width="15%">
										{if isset($order_detail.image) && $order_detail.image->id}
											{$order_detail.image_tag}
										{/if}
									</td>
									<td width="5%">&nbsp;</td>
									<td width="80%">
										{$order_detail.product_name}<br>
										{$product.modele} / {$product.annee} / {$product.config} / {$product.gamme} / {$product.couleur} / {$product.code_gabarit}
									</td>
								</tr>
							</table>
						{else}
							{$order_detail.product_name}<br>
							{$product.modele} / {$product.annee} / {$product.config} / {$product.gamme} / {$product.couleur} / {$product.code_gabarit}
						{/if}
					</td>
					<td class="product left">
						{if empty($product.reference)}
							---
						{else}
							{$product.reference}
						{/if}
					</td>
					<td class="product center">
						{$product.quantity}
					</td>

				</tr>
			{/if}

		{/foreach}
		<!-- END PRODUCTS -->
	</tbody>

</table>