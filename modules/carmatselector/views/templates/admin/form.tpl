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
    <form class="flex flex-col w-1/2 mx-auto justify-around" action="{$adminAjaxUrl}&action=saveVersionForm" method="post">
        <input class="text-slate-500 placeholder:text-slate-100 placeholder:italic" type="text"
            placeholder="Nom du véhicule (exemple: Peugeot 208 de 05/2019 à 09/2021)" name="input-name" id="input-name">
        <select class="m-2 valid:bg-green-200 invalid:bg-red-200" name="select-brand" id="select-brand" required>
            <option value="">Sélectionner une marque</option>
            {foreach $brands as $brand}
                <option value="{$brand.id}">{$brand.name}</option>
            {/foreach}
        </select>
        <select class="m-2 valid:bg-green-200 invalid:bg-red-200" name="select-model" id="select-model" required>
            <option value="">Sélectionner un model</option>
        </select>
        <select class="m-2 valid:bg-green-200 invalid:bg-red-200" name="select-carbody" id="select-carbody" required>
            <option value="">Sélectionner une carrosserie</option>
            {foreach $carbodies as $carbody}
                <option value="{$carbody.id}">{$carbody.name}</option>
            {/foreach}
        </select>
        <select class="m-2 valid:bg-green-200 invalid:bg-red-200" name="select-attachment" id="select-attachment"
            required>
            <option value="">Sélectionner une fixation</option>
            {foreach $attachments as $attachment}
                <option value="{$attachment.id}">{$attachment.name}</option>
            {/foreach}
        </select>
        <input
            class="text-slate-500 placeholder:text-slate-100 placeholder:italic valid:bg-green-200 invalid:border-red-200"
            name="gabarit" type="text" placeholder="Gabarit" required>
        <div>
            <label for="active">Activer</label>
            <input name="active" type="checkbox" name="active" id="active" value="1">
        </div>
        <input type="submit" value="Submit">
    </form>
</body>

</html>

<script>
    console.log('test script');
    window.CARMAT_ADMIN_AJAX_URL = '{$adminAjaxUrl}'+'&action=saveVersionForm';


    window.CARMAT_ADMIN_FORM_AJAX_URL = '{$adminAjaxUrl}';
const onBrandChange = function(e) {
        const brandId = e.target.value;
        if (brandId === 'null') {
            return;
        }
        fetch(window.CARMAT_ADMIN_FORM_AJAX_URL + '&action=getModels&brandId=' + brandId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': window.SECURITY_TOKEN
            },
            body: JSON.stringify({
                action: 'displayAjax',
                brandId: brandId
            })
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            const selectModel = document.getElementById('select-model');
            console.log(data['data']);
            selectModel.innerHTML = '';
            data['data'].forEach(function(model) {
                const option = document.createElement('option');
                console.log(model);
                console.log(model.id);
                console.log(model.name);

                option.value = model.id;
                option.innerHTML = model.name;
                selectModel.appendChild(option);
            });
        });
    };
    document.getElementById('select-brand').addEventListener('change', onBrandChange);
</script>
