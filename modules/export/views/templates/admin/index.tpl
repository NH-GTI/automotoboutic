<h2>Imprimer les PDF de pr&eacute;paration</h2>
{* {$smarty.now|date_format:"%Y-%m-%d"} *}

<div class="row">
	<div class="col-md-6">

		<div class="panel">
			<div class="panel-heading"><i class="icon-calendar"></i> {l s='Par date'}</div>
				<form action="{$url_submit}" method="post">
				<div class="form-group">
					<div class="input-group">
					    <span class="input-group-addon">{l s='Du'}</span>
					    <input type="date" size="4" maxlength="10" name="date_from" value="{$today}" placeholder="{l s='Format : 2007-12-31'}" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
					    <span class="input-group-addon">{l s='au'}</span>
					    <input type="date" size="4" maxlength="10" name="date_to" value="{$today}" placeholder="{l s='Format : 2007-12-31'}" class="form-control" />
					</div>
				</div>

				<div class="form-group">
					<input type="submit" value="{l s='Générer le PDF'}" name="submitPrint" class="btn btn-primary pull-right" />
				</div>
				<div class="clear"></div>
				</form>
		</div>

	</div>
	<div class="col-md-6">

		<div class="panel">
			<div class="panel-heading"><i class="icon-calendar"></i> {l s='Feuille récapitulative'}</div>
				<form action="{$url_submit}" method="post">
				<div class="form-group">
					<div class="input-group">
					    <span class="input-group-addon">{l s='Du'}</span>
					    <input type="date" size="4" maxlength="10" name="date_from" value="{$today}" placeholder="{l s='Format : 2007-12-31'}" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
					    <span class="input-group-addon">{l s='au'}</span>
					    <input type="date" size="4" maxlength="10" name="date_to" value="{$today}" placeholder="{l s='Format : 2007-12-31'}" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<input type="submit" value="{l s='Générer le fichier Excel'}" name="submitCSV" class="btn btn-primary pull-right" />
				</div>
				<div class="clear"></div>
				</form>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-md-6">

	<div class="panel">
		<div class="panel-heading"><i class="icon-tags"></i> {l s='Par statuts'}</div>
			<form action="{$url_submit}" method="post">
				<label>{l s='Statuts :'}</label>
				<div class="box_status">

                {foreach from=$statuses item=status}
					<div class="checkbox">
						<label for="id_order_state_{$status.id_order_state}" style="font-weight:700;">
							<input type="checkbox" name="id_order_state[]" value="{$status.id_order_state}" id="id_order_state_{$status.id_order_state}">
			                {if $status.invoice == 1}
								<img src="{$url_module}img/charged_ok.gif" alt="" />
			                {else}
								<img src="{$url_module}img/charged_ko.gif" alt="" />
			                {/if}
							{$status.name}
			                <span class="badge">
			                {if $statusStats[$status['id_order_state']] && $statusStats[$status['id_order_state']]}
							{$statusStats[$status['id_order_state']]}
			                {else}
							0
			                {/if}</span>
						</label>	
					</div>
                {/foreach}

				</div>
				<div class="form-group">
					<input type="submit" value="{l s='Générer le PDF'}" name="submitStatus" class="btn btn-primary pull-right" />
				</div>
				<div class="clear"></div>
			</form>
	</div>

	</div>
</div>