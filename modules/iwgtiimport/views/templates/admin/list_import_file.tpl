<div class="panel">
    <form action="" method="post" id="import_file_form" name="import_file_form" class="form-inline" enctype="multipart/form-data">
        <div class="panel-heading">
            <i class="icon-book"></i>Liste des fichiers disponibles
        </div>
        <div class="row moduleconfig-header">
            <div class="margin-form">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Cible</th>
                            <th>Fichier</th>
                            <th>Selection</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {if $files}
                        {foreach $files item=file}
                            {if $edit && $edit == $file.id_file}<input type="hidden" id="id_import_file" name="id_import_file" value="{$file.id_file}"/>{/if}
                            {if $edit && $edit == $file.id_file}<input type="hidden" id="type_file" name="type_file" value="importfile"/>{/if}
                            <tr>
                                <td>{if $edit && $edit == $file.id_file}<input size="10%" type="text" name="type" id="type" value="{$file.type}"/>{else}{$file.type}{/if}</td>
                                <td>{if $edit && $edit == $file.id_file}<input size="10%" type="text" name="target" id="target" value="{$file.target}"/>{else}{$file.target}{/if}</td>
                                <td>
                                    {if $edit && $edit == $file.id_file}
                                        <div class="dummyfile input-group">
                                            <input id="inputfile" type="file" name="inputfile" class="hide-file-upload">                                        
			                                <span class="input-group-addon"><i class="icon-file"></i></span>
			                                <input id="inputfile-name" size="90%" class="disabled" type="text" name="filename" readonly="">
			                                <span class="input-group-btn">
				                                <button id="inputfile-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
					                                <i class="icon-folder-open"></i>Ajouter un fichier
                                                </button>
                                                <a class="btn btn-default uploadfile">Transférer le fichier sur le serveur <i class="icon-external-link"></i></a>                                                                                                                                    
							                </span>
		                                </div>
                                    {else}
                                        {$file.filename}
                                    {/if}
                                </td>
                                <td>
                                    <input type="checkbox" name="file_selected" {if !$edit}class="file_selection"{/if} data-id="{$file.id_file}" id="file_selected_{$file.id_file}" {if $file.selected} checked="checked"{/if}/>
                                </td>
                                <td style="width:20%">
                                    {if $edit && $edit == $file.id_file}
                                        <button style="display:none" type="submit" value="1" id="save_import_file" name="save_import_file" class="btn btn-default pull-right">
                                            <i class="process-save"></i>Enregistrer
                                        </button>
                                        <a class="btn btn-default"
                                            href="{$link->getAdminLink('AdminModules')}&configure=iwgtiimport">
                                            <i class="icon-cancel"></i>
                                            {l s='Cancel' d='Admin.Actions'}
                                        </a>
                                    {else if !$edit}
                                        <button type="submit" value="{$file.id_file}" id="delete_import_file_{$file.id_file}" name="delete_import_file" class="btn btn-default">
                                            <i class="icon-trash process-delete"></i>Supprimer
                                        </button>
                                        {*<a class="btn btn-default pull-right"
                                            href="{$link->getAdminLink('AdminModules')}&configure=iwgtiimport&edit&id_file={$file.id_file}">
                                            <i class="icon-edit"></i>
                                            {l s='Edit' d='Admin.Actions'}
                                        </a>*}
                                    {/if}                     
                                </td>
                            </tr>
                        {/foreach}
                        {/if}
                    </tbody>
                </table>
            </div>
        </div>
        {if !$edit}
        <div class="panel-footer">
            <button type="submit" value="1" id="add_import_file" name="add_import_file" class="btn btn-default pull-right">
                <i class="process-icon-plus"></i>Ajouter
            </button>
        </div>
        {/if}
    </form>
</div>
