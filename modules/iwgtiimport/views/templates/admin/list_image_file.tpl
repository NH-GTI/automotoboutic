<div class="panel">
    <form action="" method="post" id="image_file_form" name="image_file_form" class="form-inline" enctype="multipart/form-data">
        <div class="panel-heading">
            <i class="icon-book"></i>Liste des images disponibles
        </div>
        <div class="row moduleconfig-header">
            <div class="margin-form">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Répertoire</th>
                            <th>Fichier</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {if $files}
                        {foreach $files item=file}
                            {if $edit && $edit == $file.id_file}<input type="hidden" id="id_image_file" name="id_image_file" value="{$file.id_file}"/>{/if}
                            {if $edit && $edit == $file.id_file}<input type="hidden" id="type_file" name="type_file" value="imagefile"/>{/if}
                            <tr>
                                <td>{if $edit && $edit == $file.id_file}<input size="10%" type="text" name="subpath" id="subpath" value="{$file.subpath}"/>{else}{$file.subpath}{/if}</td>
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
                                <td style="width:20%">
                                    {if $edit && $edit == $file.id_file}
                                        <button style="display:none" type="submit" value="1" id="save_image_file" name="save_image_file" class="btn btn-default pull-right">
                                            <i class="process-save"></i>Enregistrer
                                        </button>
                                        <a class="btn btn-default"
                                            href="{$link->getAdminLink('AdminModules')}&configure=iwgtiimport">
                                            <i class="icon-cancel"></i>
                                            {l s='Cancel' d='Admin.Actions'}
                                        </a>
                                    {else if !$edit}
                                        <button type="submit" value="{$file.id_file}" id="delete_image_file_{$file.id_file}" name="delete_image_file" class="btn btn-default">
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
            <button type="submit" value="1" id="add_image_file" name="add_image_file" class="btn btn-default pull-right">
                <i class="process-icon-plus"></i>Ajouter
            </button>
        </div>
        {/if}
    </form>
</div>
