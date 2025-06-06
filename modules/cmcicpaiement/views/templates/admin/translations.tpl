{*
* 2007-2015 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*}

<div class="clearfix"></div>
<div class="panel">
	<h3><i class="icon-cogs"></i> Configuration</h3>
	<div id="tab_translation" class="input-group">
		<select id="form-field-2" name="select_translation" class="selectpicker show-menu-arrow" title-icon="icon-flag" title="{l s='Manage translations' mod='cmcicpaiement'}">
			{foreach $lang_select as $lang}
				<option href="{$module_trad|escape:'htmlall':'UTF-8'}{$lang@key}&#35;{$module_name}" {if !empty($lang.subtitle)}data-subtext="{$lang.subtitle}"{/if}>{$lang.title}</a></option>
			{/foreach}
		</select>
	</div>
</div>