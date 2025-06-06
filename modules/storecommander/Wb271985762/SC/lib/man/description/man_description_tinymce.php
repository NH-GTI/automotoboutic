<?php
if (!defined('STORE_COMMANDER')) { exit; }

$id_lang = (int) Tools::getValue('id_lang');
$id_manufacturer = (int) Tools::getValue('id_manufacturer', 0);
$descriptions = array('short_description' => '', 'description' => '');
if ($id_manufacturer != 0)
{
    $sql = 'SELECT short_description,description FROM '._DB_PREFIX_."manufacturer_lang WHERE id_manufacturer=".(int) $id_manufacturer." AND id_lang=".(int) $id_lang;
    $descriptions = Db::getInstance()->getRow($sql);
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
        if (file_exists(SC_TOOLS_DIR.'man_description/tiny_config.php'))
        {
            require_once SC_TOOLS_DIR.'man_description/tiny_config.php';
        }
        else
        {
            require_once 'tiny_config.php';
        }
        ?>
    });

    function checkSizetMCE() {
        var tiny=$('#short_description').tinymce();
        window.top.prop_tb.setItemText('txt_descriptionsize','<?php echo _l('Short description charset', 1)._l(':'); ?> '+tiny.getContent().length+'/<?php echo _s('MAN_SHORT_DESC_SIZE'); ?>');
        return true;
    }
    function checkSize() {
        var tiny=$('#short_description').tinymce();
        if (tiny.getContent().replace(/<[^>]+>/g, '').length <= <?php echo _s('MAN_SHORT_DESC_SIZE'); ?>) return true;
        return false;
    }
    var tMCE1=0;
    var tMCE2=0;
    var tMCE1Content=0;
    var tMCE2Content=0;

    function ajaxLoad(args,id_manufacturer,id_lang) {
        if (tMCE1==0) tMCE1 = $('#short_description').tinymce();
        if (tMCE2==0) tMCE2 = $('#description').tinymce();
        $('#id_manufacturer').val(id_manufacturer);
        $('#id_lang').val(id_lang);
        tMCE1.setProgressState(1);
        tMCE2.setProgressState(1);
        $.get("index.php?ajax=1&act=man_description_get&content=short_description"+args, function(data){
            tMCE1.setProgressState(0);
            tMCE1.setContent(data);
            tMCE1Content=data;
            tMCE1.isNotDirty=1; // change modified state of tinyMCE
            checkSizetMCE();
        });
        $.get("index.php?ajax=1&act=man_description_get&content=description"+args, function(data){
            tMCE2.setProgressState(0);
            tMCE2.setContent(data);
            tMCE2Content=data;
            tMCE2.isNotDirty=1; // change modified state of tinyMCE
        });
    }
    function ajaxSave() {
        if (tMCE1==0) tMCE1 = $('#short_description').tinymce();
        if (tMCE2==0) tMCE2 = $('#description').tinymce();
        tMCE1.setProgressState(1);
        tMCE2.setProgressState(1);
        $.post("index.php", $("#form_descriptions").serialize(), function(data){
            tMCE1.setProgressState(0);
            tMCE2.setProgressState(0);
            if (data=='OK')
            {
                tMCE1.isNotDirty=1;
                tMCE2.isNotDirty=1;
            }else{
                if (data=='ERR|short_description_size')
                {
                    alert('<?php echo _l('Short description size must be < ', 1)._s('MAN_SHORT_DESC_SIZE'); ?>');
                }
                <?php if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) { ?>
                if (data=='ERR|short_description_with_iframe')
                {
                    alert('<?php echo _l('Short description can\'t include an iframe or is invalid', 1); ?>');
                }
                if (data=='ERR|description_with_iframe')
                {
                    alert('<?php echo _l('Description can\'t include an iframe or is invalid', 1); ?>');
                }
                if (data=='ERR|short_description_invalid')
                {
                    alert('<?php echo _l('Short description is invalid', 1); ?>');
                }
                if (data=='ERR|description_invalid')
                {
                    alert('<?php echo _l('Description is invalid', 1); ?>');
                }
                <?php } ?>
            }
        });
    }
    function checkChange() {
        if (tMCE1==0) tMCE1 = $('#short_description').tinymce();
        if (tMCE2==0) tMCE2 = $('#description').tinymce();
        <?php if (_s('MAN_NOTICE_SAVE_DESCRIPTION')) { ?>
        if (tMCE1.isDirty() || tMCE2.isDirty())
            if (confirm('<?php echo _l('Do you want to save the descriptions?', 1); ?>'))
                ajaxSave();
        <?php } ?>
    }


    function showShortDesc()
    {
        $("#container_short_description").show();
    }
    function hideShortDesc()
    {
        $("#container_short_description").hide();
    }
</script>
<form id="form_descriptions" method="POST">
    <input name="ajax" type="hidden" value="1"/>
    <input name="act" type="hidden" value="man_description_update"/>
    <input id="id_manufacturer" name="id_manufacturer" type="hidden" value="<?php echo $id_manufacturer; ?>"/>
    <input id="id_lang" name="id_lang" type="hidden" value="<?php echo $id_lang; ?>"/>
    <div id="container_short_description">
        <textarea id="short_description" name="short_description" class="tinymce1 rte" cols="50" rows="10" style=""><?php echo $descriptions['short_description']; ?></textarea>
    </div>
    <textarea id="description" name="description" class="tinymce2 rte" cols="50" rows="30" style=""><?php echo $descriptions['description']; ?></textarea>
</form>
</body>
</html>
