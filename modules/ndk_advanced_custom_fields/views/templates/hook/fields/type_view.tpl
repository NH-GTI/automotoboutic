{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

<ul class="replace-img-block">
	{foreach from=$field.values item=value name=value}
		<li class="view_tab {if $value.issvg}svg {else} jpg{/if}" data-id="{$field.id_ndk_customization_field|escape:'htmlall':'UTF-8'}" data-view="{$value.id|intval}" data-img="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{$value.id|escape:'intval'}{if !$value.issvg}{/if}{if $value.issvg}.svg{else}.jpg{/if}">{$value.value|escape:'htmlall':'UTF-8'}</li>
		<!--<div class="layers" data-view="{$value.id|intval}"></div>-->
		<input type="hidden" id="ndkcsfieldPdf_{$field.id_ndk_customization_field|escape:'intval'}" name="ndkcsfieldPdf[{$field.id_ndk_customization_field|escape:'intval'}]"/>
		{if $value.issvg}
		<div class="svg-container hidden" id="ndkcsfieldSVGView_{$value.id|intval}">{$value.svgcode nofilter}</div>
		{/if}
		
	{/foreach}
</ul>
{foreach from=$field.values item=value name=value}
	<input type="hidden" name="image-url[]" value="" id="image-url-{$value.id|intval}" class="image-url"/>
	
{/foreach}

