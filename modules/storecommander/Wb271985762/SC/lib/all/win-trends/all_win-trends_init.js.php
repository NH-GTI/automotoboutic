<?php
if (!defined('STORE_COMMANDER')) { exit; }
echo '<script>'; ?>
    dhxlTrendsProject=wTrendsProject.attachLayout("1C");

    // Content
    var dhxlRowContent = dhxlTrendsProject.cells('a');
    dhxlRowContent.hideHeader();
    dhxlRowContent.attachURL('index.php?ajax=1&act=all_win-trends_desc&id_lang='+SC_ID_LANG);
<?php echo '</script>'; ?>
