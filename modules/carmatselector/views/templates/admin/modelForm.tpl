<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    {* form add new version *}
    <form class="flex flex-col w-1/2 mx-auto justify-around" action="{$adminAjaxUrl}&action=saveModelForm"
        method="post">
        <input class="text-slate-500 placeholder:text-slate-100 placeholder:italic" type="text"
            placeholder="Nom du modèle (exemple: Clio)" name="input-name" id="input-name">
        <select class="m-2 valid:bg-green-200 invalid:bg-red-200" name="select-brand" id="select-brand" required>
            <option value="">Sélectionner une marque</option>
            {foreach $brands as $brand}
                <option value="{$brand.id}">{$brand.name}</option>
            {/foreach}
        </select>
        <div>
            <label for="active">Activer</label>
            <input name="active" type="checkbox" name="active" id="active" value="1">
        </div>
        <input type="submit" value="Submit">
    </form>
</body>

</html>