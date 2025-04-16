{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

	{foreach from=$field.values item=value name='mesures'}
		{if $smarty.foreach.mesures.index < 2}
		<p>
			<input id="ndkcsfield_{$field.id_ndk_customization_field|escape:'intval'}_{$value.id|escape:'intval'}" data-message="{l s='Informe' mod='ndk_advanced_custom_fields'} {$value.value|escape:'htmlall':'UTF-8'}"  placeholder="{$value.value|escape:'htmlall':'UTF-8'}" name="ndkcsfield[{$field.id_ndk_customization_field|escape:'intval'}][surface][{$value.id|escape:'htmlall':'UTF-8'}]" data-val="{$smarty.foreach.mesures.index}" data-group="{$field.id_ndk_customization_field|escape:'intval'}" data-price="{$fieldPrice|escape:'htmlall':'UTF-8'}" type="text" class="form-control surface surface_{$field.id_ndk_customization_field|escape:'intval'} {if $field.required == 1} required_field{/if}" data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" 
			 data-preserve-ratio="{$field.preserve_ratio}" step="{$value.step_quantity}"
			{if $value.quantity_max > 0}
			data-qtty-max="{$value.quantity_max}" max="{$value.quantity_max}" 
			{/if}
			{if $value.quantity_min > 0}
			data-qtty-min="{$value.quantity_min}"  min="{$value.quantity_min}"
			{/if}
			data-step_quantity="" size="8"
			/>
			<span class="quantity-ndk-minus btn-default btn"  data-target-class="surface"><i class="icon-minus"></i></span>
			<span class="quantity-ndk-plus btn-default btn" data-target-class="surface"><i class="icon-plus"></i></span>
		</p>
		{/if}
	{/foreach}
