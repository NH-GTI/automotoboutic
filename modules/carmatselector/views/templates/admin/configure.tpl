{* {extends file='page.tpl'} *}

{block name="override_tpl"}
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        window.CARMAT_ADMIN_DATA = {$data nofilter};
        window.CARMAT_ADMIN_AJAX_URL = '{$adminAjaxUrl}';
        window.CARMAT_ADMIN_TYPE = '{$type}';
        window.CARMAT_ADMIN_LINK_URL = '{$adminLinkUrl}';
        window.CARMAT_ADMIN_FORM_SUCCESS = '{$success}';
        window.SECURITY_TOKEN = '{$token|escape:'html':'UTF-8'}';
    </script>
    <div id="carmat-admin-app"></div>
    <script src="{$module_dir|escape:'html':'UTF-8'}views/js/admin.js"></script>
{/block}