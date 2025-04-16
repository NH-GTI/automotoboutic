{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

{foreach from=$field.values item=value name="mask"}
	{if $smarty.foreach.mask.last}
		<span class="hidden ndkmask" data-src="{if isset($is_https) && $is_https}{$base_dir_ssl}{else}{$base_dir}{/if}img/scenes/ndkcf/{$value.id|escape:'intval'}.jpg"  data-id="{$field.target|escape:'htmlall':'UTF-8'}" data-view="{$field.target_child|escape:'htmlall':'UTF-8'}" data-zindex="{$field.zindex|escape:'htmlall':'UTF-8'}"></span>
	{/if}
{/foreach}