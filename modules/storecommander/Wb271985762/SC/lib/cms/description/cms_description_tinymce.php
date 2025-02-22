<?php
if (!defined('STORE_COMMANDER')) { exit; }

    $id_lang = (int) Tools::getValue('id_lang');
    $id_cms = (int) Tools::getValue('id_cms', 0);
    $id_shop = (int) Tools::getValue('id_shop', 0);
    $content = null;

    if ($id_cms != 0)
    {
        $sql = 'SELECT content FROM '._DB_PREFIX_."cms_lang WHERE id_cms='".(int) $id_cms."' AND id_lang='".(int) $id_lang."'";
        if (version_compare(_PS_VERSION_, '1.6.0.12', '>='))
        {
            $sql .= ' AND id_shop='.(int) $id_shop;
        }
        $content = Db::getInstance()->getValue($sql);
    }
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo SC_CSSSTYLE; ?>" />
    <script src="<?php echo SC_JQUERY; ?>"></script>
    <script src="lib/js/tiny_mce/tiny_mce.js"></script>
    <script src="lib/js/tiny_mce/jquery.tinymce.js"></script>
</head>
<body style="padding:0px;margin:0px;">
<?php
        $iso = UISettings::getSetting('forceSCLangIso');
        if (empty($iso))
        {
            $iso = Language::getIsoById((int) ($sc_agent->id_lang));
        }
        echo '
<script>
var iso = \''.(file_exists('lib/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'\' ;
var pathCSS = \''._THEME_CSS_DIR_.'\' ;
var pathTiny = \'lib/js/tiny_mce/tiny_mce.js\' ;
var add = \'lib/js/\' ;
</script>';
?>
<script>
    $().ready(function() {
<?php
    if (file_exists(SC_TOOLS_DIR.'cms_description/tiny_config.php'))
    {
        require_once SC_TOOLS_DIR.'cms_description/tiny_config.php';
    }
    else
    {
        require_once 'tiny_config.php';
    }
?>
    });
var tMCE=0;
var tMCEContent=0;

function ajaxLoad(args,id_cms,id_lang,id_shop) {
    if (tMCE==0) tMCE = $('#content').tinymce();
    $('#id_cms').val(id_cms);
    $('#id_lang').val(id_lang);
    $('#id_shop').val(id_shop);
    tMCE.setProgressState(1);
    $.get("index.php?ajax=1&act=cms_description_get&content=content&id_shop="+id_shop+args, function(data){
        tMCE.setProgressState(0);
        tMCE.setContent(data);
        tMCEContent=data;
        tMCE.isNotDirty=1; // change modified state of tinyMCE
        });
}
function ajaxSave() {
    if (tMCE==0) tMCE = $('#content').tinymce();
    tMCE.setProgressState(1);
    $.post("index.php?act=cms_description_update", $("#form_content").serialize(), function(data){
            tMCE.setProgressState(0);
            if (data=='OK')
            {
                tMCE.isNotDirty=1;
            }else{
                <?php if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) { ?>
                if (data=='ERR|content_with_iframe')
                {
                    alert('<?php echo _l('Content can\'t include an iframe or is invalid', 1); ?>');
                }
                if (data=='ERR|content_invalid')
                {
                    alert('<?php echo _l('Content is invalid', 1); ?>');
                }
                if (data=='ERR|process')
                {
                    alert('<?php echo _l('Error during process', 1); ?>');
                }
                <?php } ?>
            }
        });
}
function checkChange() {
    if (tMCE==0) tMCE = $('#content').tinymce();
    <?php if (_s('CMS_NOTICE_SAVE_DESCRIPTION')) { ?>
    if (tMCE.isDirty() || tMCE.isDirty())
       if (confirm('<?php echo _l('Do you want to save the content?', 1); ?>'))
           ajaxSave();
    <?php } ?>
}

</script>
<form id="form_content" method="POST">
<input name="ajax" type="hidden" value="1"/>
<input name="act" type="hidden" value="cms_description_update"/>
<input id="id_cms" name="id_cms" type="hidden" value="<?php echo $id_cms; ?>"/>
<input id="id_lang" name="id_lang" type="hidden" value="<?php echo $id_lang; ?>"/>
<input id="id_shop" name="id_shop" type="hidden" value="<?php echo $id_shop; ?>"/>
<textarea id="content" name="content" class="tinymce rte" cols="50" rows="30" style=""><?php echo $content; ?></textarea>
</form>
</body>
</html>
