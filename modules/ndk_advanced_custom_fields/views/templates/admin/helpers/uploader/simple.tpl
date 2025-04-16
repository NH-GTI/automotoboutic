{*
 *  Tous droits réservés NDKDESIGN
 *
 *  @author Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*}

{if isset($files) && $files|count > 0}
	{assign var='show_thumbnail' value=false}
	{foreach $files as $file}
		{if isset($file.image) && $file.type == 'image'}
			{assign var='show_thumbnail' value=true}
		{/if}
	{/foreach}
{if $show_thumbnail}
<div class="form-group">
	<div class="col-lg-12" id="{$id|escape:'htmlall':'UTF-8'}-images-thumbnails">
		{foreach $files as $file}
		{if isset($file.image) && $file.type == 'image'}
		<div>
			{$file.image|escape:'htmlall':'UTF-8'}
			{if isset($file.size)}<p>{l s='File size' mod='ndk_advanced_custom_fields'} {$file.size|escape:'htmlall':'UTF-8'}{l s='kb' mod='ndk_advanced_custom_fields'}</p>{/if}
			{if isset($file.delete_url)}
			<p>
				<a class="btn btn-default" href="{$file.delete_url|escape:'htmlall':'UTF-8'}">
					<i class="icon-trash"></i> {l s='Delete' mod='ndk_advanced_custom_fields'}
				</a>
			</p>
			{/if}
		</div>
		{/if}
		{/foreach}
	</div>
</div>
{/if}
{/if}
{if isset($max_files) && $files|count >= $max_files}
<div class="row">
	<div class="alert alert-warning">{l s='You have reached the limit (%s) of files to upload, please remove files to continue uploading' mod='ndk_advanced_custom_fields' sprintf=$max_files}</div>
</div>
{else}
<div class="form-group">
	<div class="col-sm-6">
		<input id="{$id|escape:'htmlall':'UTF-8'}" type="file" name="{$name|escape:'htmlall':'UTF-8'}"{if isset($multiple) && $multiple} multiple="multiple"{/if} class="hide" />
		<div class="dummyfile input-group">
			<span class="input-group-addon"><i class="icon-file"></i></span>
			<input id="{$id|escape:'htmlall':'UTF-8'}-name" type="text" name="filename" readonly />
			<span class="input-group-btn">
				<button id="{$id|escape:'htmlall':'UTF-8'}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
					<i class="icon-folder-open"></i> {if isset($multiple) && $multiple}{l s='Add files' mod='ndk_advanced_custom_fields'}{else}{l s='Add file' mod='ndk_advanced_custom_fields'}{/if}
				</button>
				{if (!isset($multiple) || !$multiple) && isset($files) && $files|count == 1 && isset($files[0].download_url)}
				<a href="{$files[0].download_url|escape:'htmlall':'UTF-8'}">
					<button type="button" class="btn btn-default">
						<i class="icon-cloud-download"></i>
						{if isset($size)}{l s='Download current file (%skb)' sprintf=$size mod='ndk_advanced_custom_fields'}{else}{l s='Download current file' mod='ndk_advanced_custom_fields'}{/if}
					</button>
				</a>
				{/if}
			</span>
		</div>
	</div>
</div>
<script type="text/javascript">
{if isset($multiple) && isset($max_files)}
	var {$id|escape:'htmlall':'UTF-8'}_max_files = {$max_files - $files|count};
{/if}

	$(document).ready(function(){
		$('#{$id|escape:"intval"}-selectbutton').click(function(e) {
			$('#{$id|escape:"intval"}').trigger('click');
		});

		$('#{$id|escape:"intval"}-name').click(function(e) {
			$('#{$id|escape:"intval"}').trigger('click');
		});

		$('#{$id|escape:"intval"}-name').on('dragenter', function(e) {
			e.stopPropagation();
			e.preventDefault();
		});

		$('#{$id|escape:"intval"}-name').on('dragover', function(e) {
			e.stopPropagation();
			e.preventDefault();
		});

		$('#{$id|escape:"intval"}-name').on('drop', function(e) {
			e.preventDefault();
			var files = e.originalEvent.dataTransfer.files;
			$('#{$id|escape:"intval"}')[0].files = files;
			$(this).val(files[0].name);
		});

		$('#{$id|escape:"intval"}').change(function(e) {
			if ($(this)[0].files !== undefined)
			{
				var files = $(this)[0].files;
				var name  = '';

				$.each(files, function(index, value) {
					name += value.name+', ';
				});

				$('#{$id|escape:"intval"}-name').val(name.slice(0, -2));
			}
			else // Internet Explorer 9 Compatibility
			{
				var name = $(this).val().split(/[\\/]/);
				$('#{$id|escape:"intval"}-name').val(name[name.length-1]);
			}
		});

		if (typeof {$id|escape:'htmlall':'UTF-8'}_max_files !== 'undefined')
		{
			$('#{$id|escape:"intval"}').closest('form').on('submit', function(e) {
				if ($('#{$id|escape:"intval"}')[0].files.length > {$id|escape:'htmlall':'UTF-8'}_max_files) {
					e.preventDefault();
					alert('{l s='You can upload a maximum of %s files'|sprintf:$max_files mod='ndk_advanced_custom_fields'}');
				}
			});
		}
	});
</script>
{/if}