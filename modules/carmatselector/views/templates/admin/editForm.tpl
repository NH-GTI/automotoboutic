<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le modèle</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <form class="flex flex-col w-1/2 mx-auto justify-around" action="{$adminAjaxUrl}&action=updateForm" method="post">
        <input type="hidden" name="childID" value="{$dataToEdit.id}">

        <input class="text-slate-500 placeholder:text-slate-100 placeholder:italic" type="text"
            placeholder="Nom du modèle (exemple: Clio)" name="input-name" id="input-name" value="{$dataToEdit.name}">

        {if $type != '1'}
            <select class="m-2 valid:bg-green-200 invalid:bg-red-200" name="parentID" id="parentID" required>
                <option value="">Sélectionner une
                    {{if $type == '2'}}modèle{{elseif $type == '3'}}version{{elseif $type == '4'}}couleur{{elseif $type == '5'}}carrosserie{{elseif $type == '6'}}fixation{{/if}}
                </option>
                {foreach $datas as $data}
                    <option value="{$data.id}" {if $data.id == $dataToEdit.id_carmatselector_brand}selected{/if}>
                        {$data.name}
                    </option>
                {/foreach}
            </select>
        {/if}

        <div>
            <label for="active">Activer</label>
            <input name="active" type="checkbox" id="active" value="1" {if $dataToEdit.active}checked{/if}>
        </div>

        <input type="hidden" name="type" value="{$type}">

        <input type="submit" value="Mettre à jour"
            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
    </form>
</body>

</html>