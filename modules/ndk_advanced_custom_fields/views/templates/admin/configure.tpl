{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

<div class="panel">
	<h3><i class="icon icon-edit"></i> {l s='Ndk Advanced Customization Fields' mod='ndk_advanced_custom_fields'}</h3>
	<br/>
	<h4><i class="icon icon-tags"></i> {l s='Purge unused customization' mod='ndk_advanced_custom_fields'}</h4>
	<p>
		{l s='Here is an url to purge unused customization' mod='ndk_advanced_custom_fields'} <br/>
		{l s='It will delete products and customization generated by module, which have not been ordered, and older than 5 days' mod='ndk_advanced_custom_fields'}<br/>
		{l s='You can add it to your crontab to automate the process' mod='ndk_advanced_custom_fields'}
		<a class="button" target="_blank" href="{$base_url|escape:'htmlall':'UTF-8'}/modules/ndk_advanced_custom_fields/purgeVirtualsProducts.php">{$base_url|escape:'htmlall':'UTF-8'}/modules/ndk_advanced_custom_fields/purgeVirtualsProducts.php</a>
	</p>
	<br/><br/>
	<h4><i class="icon icon-tags"></i> {l s='Your API KEY' mod='ndk_advanced_custom_fields'}</h4>
	<p><input id="ndkcf_api_ley" class="autoCopy" type="text" readonly="readonly" value="{Configuration::get('NDKCF_API_KEY')}"/></p>
	<br/><br/>
	
	{if $message}
		{foreach $message as $msg}{$msg}{/foreach}
	{/if}
	
	<h4><i class="icon icon-tags"></i> {l s='Re-generate thumbnails' mod='ndk_advanced_custom_fields'}</h4>
	<form method="post">
		<input type="hidden" name="submitNkdAcfThumbnails" value="true"/>
		<button>{l s='Generate' mod='ndk_advanced_custom_fields'}</button>
	</form>
	<br/><br/>
	<h4><i class="icon icon-tags"></i> {l s='Clean fields associations' mod='ndk_advanced_custom_fields'}</h4>
	<form method="post">
		<p>{l s='will remove association where product or category have been deleted' mod='ndk_advanced_custom_fields'}</p>
		<input type="hidden" name="submitNkdAcfcleanAssociations" value="true"/>
		<button>{l s='Clean associations' mod='ndk_advanced_custom_fields'}</button>
	</form>
	
	
	<br/><br/>
	<div id="importNdkCf" class="{if Tools::getValue('showMe') == 1}visible{else}hidden{/if}">
		<h4><i class="icon icon-tags"></i> {l s='Import from other' mod='ndk_advanced_custom_fields'}</h4>
		<form class="importAjaxFrom" method="GET"  action="{$base_url|escape:'htmlall':'UTF-8'}modules/ndk_advanced_custom_fields/import/importDatas.php">
			<div class="form-group clearfix">
				<input type="text" placeholder="{l s='Source domain' mod='ndk_advanced_custom_fields'}" name="src_domain" value=""/>
			</div>
			<div class="form-group clearfix">
				<input type="text" placeholder="{l s='Source API Key' mod='ndk_advanced_custom_fields'}" name="src_key" value=""/>
			</div>
			<div class="form-group clearfix">
				<label class="control-label ">{l s='Specific group id : ' mod='ndk_advanced_custom_fields'}</label>
				<input type="number" name="id_group"/>
			</div>
			<div class="form-group clearfix">
				<input type="hidden" name="service" value="main"/>
				<input type="hidden" name="runfields" value="0"/>
				<button type="submit">{l s='Import' mod='ndk_advanced_custom_fields'}</button>
			</div>
		</form>
	</div>
</div>


