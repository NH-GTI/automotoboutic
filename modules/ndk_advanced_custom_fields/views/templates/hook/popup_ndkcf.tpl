{if (isset($ndkcsfields) && $ndkcsfields|@count > 0) || ($fieldsItems)}
<button type="button" class="btn-primary btn-ndkacf-popup" data-toggle="modal" data-target="#ndkacf-modal">
	{if $edit_config > 0}
	  {l s='Edit your customization' mod='ndk_advanced_custom_fields'}
	  {else}
	  {l s='Customize' mod='ndk_advanced_custom_fields'}
	  {/if}
</button>
<div class="alert alert-warning popup_required hidden" role="alert">
	  {l s='Please fill option form by clicking here' mod='ndk_advanced_custom_fields'}
</div>

	
	<!-- Modal -->
	<div class="modal fade" id="ndkacf-modal" tabindex="-1" role="dialog" aria-labelledby="ndkacf-modal" aria-hidden="true">
		<div class="title_popup">
			<h5 class="modal-title">
				{if $edit_config > 0}
					{l s='Edit your customization' mod='ndk_advanced_custom_fields'}
					{else}
					{l s='Customize' mod='ndk_advanced_custom_fields'}
				{/if}
			</h5>
		</div> 
	  <div class="modal-dialog full-width" role="document">
		<div class="modal-header-ndk">
			<div class="modal-content full-width">
				<div class="sticky-responsive header-pc clear clearfix">  
					<div class="col-md-6 pull-left">
					<button type="button" id="popup-add-to-cart" class="btn btn-primary full-width add-to-cart falseButton"><span class="material-icons">shopping_cart</span>{l s='Add to cart' mod='ndk_advanced_custom_fields'}</button>
					</div>
					<div class="col-md-6 pull-right close-popup">
						<button type="button" class="close  ndkacf-close-modal" data-dismiss="modal" aria-label="Close">
							<span class="btn btn-primary" aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>	
			</div>
		</div>		
		  <div id="ndkacf_modal_body" class="modal-body clearfix">
				<div id="custom-block-popup" class="ndkacf-options animated">	
				<div id="ndkcf_mobile_options_toggler" data-toggle="tooltip" data-placement="top" title="{l s='Toggle menu' mod='ndk_advanced_custom_fields'}">
					<span class="material-icons">more_vert</span>
				</div>
					{include file='./ndkcf.tpl'}
				</div>	
				<div id="imgs-bloc-popup" class="ndk-imgs-popup">
					<div class="ndk-imgs-autoHeight">
						<div id="image-block" class="product-cover ndkacf-imgs">
							{if $product.cover}
							  <img id="bigpic" class="js-qv-product-cover" src="{$product.cover.bySize.large_default.url}" alt="{$product.cover.legend}" title="{$product.cover.legend}" style="width:100%;" itemprop="image">
							  <div class="layer hidden-sm-down" data-toggle="modal" data-target="#product-modal">
								<i class="material-icons zoom-in">&#xE8FF;</i>
							  </div>
							{else}
							  <img src="{$urls.no_picture_image.bySize.large_default.url}" style="width:100%;">
							{/if}
						</div>
					</div>	
				</div>	
		  </div>
		</div>
	  </div>
	</div>
{/if}
