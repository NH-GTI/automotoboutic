{extends file='page.tpl'}

{block name='page_content'}
    <!-- Add Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Pass PHP data to JavaScript -->
    <script>
        window.CARMAT_INITIAL_DATA = {$carmatData nofilter};
        window.CARMAT_AJAX_URL = '{$ajaxUrl nofilter}';
        window.CARMAT_TOKEN = '{$token nofilter}';
        console.log('Initial data:', window.CARMAT_INITIAL_DATA);
    </script>

    <div id="carmat-app"></div>

    <!-- Load Vue app -->
    <script src="{$urls.base_url}modules/carmatselector/views/js/app.js"></script>
{/block}