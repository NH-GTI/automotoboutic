<?php
if (!defined('STORE_COMMANDER')) { exit; }
echo '<script type="text/javascript">'; ?>
dhxlSynchroCatsPos=wSynchroCatsPos.attachLayout("1C");
dhxlSynchroCatsPos.cells('a').hideHeader();
dhxlSynchroCatsPos.cells('a').attachURL("index.php?ajax=1&act=cat_win-categorysynch_choice&id_lang="+SC_ID_LANG+"&"+new Date().getTime(),function(data){});
<?php echo '</script>'; ?>