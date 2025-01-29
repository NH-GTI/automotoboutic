
<div class="panel"><h3><i class="icon-list-ul"></i> {l s='Pushs list' d='Modules.Imagepushr.Shop'}
	<span class="panel-heading-action">
		<a id="desc-product-new" class="list-toolbar-btn" href="{$link->getAdminLink('AdminModules')}&configure=lm_pushsurmesure&addPush=1">
			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Add new' d='Admin.Actions'}" data-html="true">
				<i class="process-icon-new "></i>
			</span>
		</a>
	</span>
	</h3>
	<div id="pushsContent">
		<div id="pushs">
			{foreach from=$pushs item=push}
				<div id="pushs_{$push.id_push}" class="panel">
					<div class="row">
						<div class="col-lg-1">
							<span><i class="icon-arrows "></i></span>
						</div>
						<div class="col-md-3">
							<img src="{$image_baseurl}{$push.image}" alt="{$push.title}" class="img-thumbnail" />
						</div>
						<div class="col-md-8">
							<h4 class="pull-left">
								#{$push.id_push} - {$push.title}
								{if $push.is_shared}
									<div>
										<span class="label color_field pull-left" style="background-color:#108510;color:white;margin-top:5px;">
											{l s='Shared push' d='Modules.Imagepushr.Shop'}
										</span>
									</div>
								{/if}
							</h4>
							<div class="btn-group-action pull-right">
								{$push.status}

								<a class="btn btn-default"
									href="{$link->getAdminLink('AdminModules')}&configure=lm_pushsurmesure&id_push={$push.id_push}">
									<i class="icon-edit"></i>
									{l s='Edit' d='Admin.Actions'}
								</a>
								<a class="btn btn-default"
									href="{$link->getAdminLink('AdminModules')}&configure=lm_pushsurmesure&delete_id_push={$push.id_push}">
									<i class="icon-trash"></i>
									{l s='Delete' d='Admin.Actions'}
								</a>
							</div>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
	</div>
</div>
