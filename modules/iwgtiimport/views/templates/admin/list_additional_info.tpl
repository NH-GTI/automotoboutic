<div class="panel">
    <form action="" method="post" id="additional_info_form" name="additional_info_form" class="form-inline">
        <div class="panel-heading">
            <i class="icon-book"></i>Informations additionnelles
        </div>
        <div class="row moduleconfig-header">
            <div class="margin-form">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Produit</th>
                            <th>Référence du champ</th>
                            <th>Position</th>
                            <th>Affichage ?</th>
                            <th>Préfixe information addionnelle</th>
                            <th>Nom colonnes de référence</th>
                            <th>Valeur par pays</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {if $informations}
                        {foreach $informations item=info}
                            {if $edit && $edit == $info.id_information}<input type="hidden" id="id_information" name="id_information" value="{$info.id_information}"/>{/if}
                            <tr>
                                <td>{if $edit && $edit == $info.id_information}<input size="10%" type="text" name="id_product" id="id_product" value="{$info.id_product}"/>{else}{$info.id_product}{/if}</td>
                                <td>{if $edit && $edit == $info.id_information}<input size="20%" type="text" name="field_reference" id="field_reference" value="{$info.field_reference}"/>{else}{$info.field_reference}{/if}</td>
                                <td>{if $edit && $edit == $info.id_information}<input size="5%" type="text" name="position" id="position" value="{$info.position}"/>{else}{$info.position}{/if}</td>
                                <td>
                                    <input type="checkbox" {if !$edit || $edit != $info.id_information}disabled="disabled"{/if} name="display" id="display_{$info.id_information}" {if $info.display} checked="checked"{/if}/>
                                </td>
                                <td>{if $edit && $edit == $info.id_information}<input size="10%" type="text" name="prefix" id="prefix" value="{$info.prefix}"/>{else}{$info.prefix}{/if}</td>
                                <td>{if $edit && $edit == $info.id_information}<input size="50%" type="text" name="key_column" id="key_column" value="{$info.key_column}"/>{else}{$info.key_column}{/if}</td>
                                <td>
                                    <input type="checkbox" {if !$edit || $edit != $info.id_information}disabled="disabled"{/if} name="value_by_country" id="value_by_country_{$info.id_information}" {if $info.value_by_country} checked="checked"{/if}/>
                                </td>   
                                <td>
                                    {if $edit && $edit == $info.id_information}
                                        <button type="submit" value="1" id="save_additional_info" name="save_additional_info" class="btn btn-default pull-right">
                                            <i class="process-save"></i>Enregistrer
                                        </button>
                                        <a class="btn btn-default"
                                            href="{$link->getAdminLink('AdminModules')}&configure=iwgtiimport">
                                            <i class="icon-cancel"></i>
                                            {l s='Cancel' d='Admin.Actions'}
                                        </a>
                                    {else if !$edit}
                                        <button type="submit" value="{$info.id_information}" id="delete_additional_info_{$info.id_information}" name="delete_additional_info" class="btn btn-default">
                                            <i class="icon-trash process-delete"></i>Supprimer
                                        </button>
                                        <a class="btn btn-default pull-right"
                                            href="{$link->getAdminLink('AdminModules')}&configure=iwgtiimport&edit&id_information={$info.id_information}">
                                            <i class="icon-edit"></i>
                                            {l s='Edit' d='Admin.Actions'}
                                        </a>
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
            <button type="submit" value="1" id="add_additional_info" name="add_additional_info" class="btn btn-default pull-right">
                <i class="process-icon-plus"></i>Ajouter
            </button>
        </div>
        {/if}
    </form>
</div>
