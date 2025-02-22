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

$iso = Language::getIsoById((int) $id_lang);

if (empty($iso))
{
    $iso = UISettings::getSetting('forceSCLangIso');
}
if (version_compare(_PS_VERSION_, '1.7.0.0', '>='))
{
    $sql = 'SELECT locale FROM '._DB_PREFIX_.'lang WHERE iso_code = "'.pSQL($iso).'"';
}
else
{
    $sql = 'SELECT language_code FROM '._DB_PREFIX_.'lang WHERE iso_code = "'.pSQL($iso).'"';
}
$lang_iso = Db::getInstance()->getValue($sql);
list($min, $maj) = explode('-', $lang_iso);
$lang_iso = strtolower($min).'_'.strtoupper($maj);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script type="text/javascript" src="<?php echo SC_JQUERY; ?>"></script>
    <script src="lib/js/ckeditor/ckeditor.js?<?php echo rand(); ?>"></script>
</head>
<body style="padding:0px;margin:0px;">
<script type="text/javascript">
    <?php echo 'var pathCSS = \''._THEME_CSS_DIR_.'\' ;'; ?>
    <?php echo 'var langIso = "'.$lang_iso.'" ;'; ?>
    <?php
    if (version_compare(_PS_VERSION_, '1.7.0.0', '>='))
    {
        echo 'var fileCSS = "theme.css" ;';
    }
    else
    {
        echo 'var fileCSS = "global.css" ;';
    }
    ?>

    var activeSCAYT = <?php echo _s('APP_CKEDITOR_AUTOCORRECT_ACTIVE') == '1' ? 'true' : 'false'; ?>;
    CKEDITOR.config.customConfig="<?php echo SC_CKEDITOR_CONFIG; ?>";
    <?php if (_s('MAN_PROPERTIES_DESCRIPTION_CSS')) { ?>CKEDITOR.config.contentsCss = pathCSS+fileCSS ;<?php } ?>
    <?php if (!_s('APP_CKEDITOR_CODESNIPPET_ACTIVE')) { ?>CKEDITOR.config.removePlugins = 'codesnippet';<?php } ?>
    CKEDITOR.config.language = '<?php echo $user_lang_iso; ?>';
    CKEDITOR.config.scayt_sLang = langIso;
    var tCKE1=0;
    var tCKE2=0;
    var tCKE1Content=0;
    var tCKE2Content=0;


    function checkSizetCKE() {
        if (tCKE1==0) tCKE1 = CKEDITOR.replace( 'short_description' , {height: (total_height*30/100) });
        window.top.prop_tb.setItemText('txt_descriptionsize','<?php echo _l('Short description charset', 1)._l(':'); ?> '+tCKE1.getData().length+'/<?php echo _s('MAN_SHORT_DESC_SIZE'); ?>');
        return true;
    }
    function checkSize() {
        if (tCKE1==0) tCKE1 = CKEDITOR.replace( 'short_description' , {height: (total_height*30/100) });
        if (tCKE1.getData().replace(/<[^>]+>/g, '').length <= <?php echo _s('MAN_SHORT_DESC_SIZE'); ?>) return true;
        return false;
    }

    function ajaxLoad(args,id_manufacturer,id_lang) {
        if (tCKE1==0) tCKE1 = CKEDITOR.replace( 'short_description' , {height: (total_height*30/100) });
        if (tCKE2==0) tCKE2 = CKEDITOR.replace( 'description' , {height: (total_height*60/100) });
        $('#id_manufacturer').val(id_manufacturer);
        $('#id_lang').val(id_lang);
        $.get("index.php?ajax=1&act=man_description_get&content=short_description"+args, function(data){
            tCKE1.setData(data);
            tCKE1Content=data;
            checkSizetCKE();
            tCKE1.resetDirty();
            setTimeout(function(){ putInBase()}, 500);
        });
        $.get("index.php?ajax=1&act=man_description_get&content=description"+args, function(data){
            parent.prop_tb._descriptionsLayout.cells('a').progressOff();
            tCKE2.setData(data);
            tCKE2Content=data;
            tCKE2.resetDirty();
            setTimeout(function(){ putInBase()}, 500);
        });
    }
    function ajaxSave() {
        if (tCKE1==0) tCKE1 = CKEDITOR.replace( 'short_description' , {height: (total_height*30/100) });
        if (tCKE2==0) tCKE2 = CKEDITOR.replace( 'description' , {height: (total_height*60/100) });
        $("#form_descriptions textarea#short_description").val(tCKE1.getData());
        $("#form_descriptions textarea#description").val(tCKE2.getData());
        $.post("index.php", $("#form_descriptions").serialize(), function(data){
            parent.prop_tb._descriptionsLayout.cells('a').progressOff();
            if (data=='OK')
            {
                tCKE1.resetDirty();
                tCKE2.resetDirty();
                setTimeout(function(){ putInBase()}, 500);
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
        if (tCKE1==0) tCKE1 = CKEDITOR.replace( 'short_description' , {height: (total_height*30/100) });
        if (tCKE2==0) tCKE2 = CKEDITOR.replace( 'description' , {height: (total_height*60/100) });
        //if (tCKE1.checkDirty() || tCKE2.checkDirty())
        <?php if (_s('MAN_NOTICE_SAVE_DESCRIPTION')) { ?>
        if(tCKE2.getData()!=$("#base_description").val() || tCKE1.getData()!=$("#base_short_description").val())
            if (confirm('<?php echo _l('Do you want to save the descriptions?', 1); ?>'))
                ajaxSave();
        <?php } ?>
    }

    $(document).ready(function(){
        tCKE1 = CKEDITOR.replace( 'short_description' , {height: (total_height*30/100) });
        tCKE1.on('key', function () {
            checkSizetCKE();
            setTimeout(function(){ checkSizetCKE(); }, 100);
        } );
        tCKE2 = CKEDITOR.replace( 'description' , {height: (total_height*60/100) });
        setTimeout(function(){ putInBase()}, 500);
        checkSizetCKE();
    });

    var total_height = parent.prop_tb._descriptionsLayout.cells('a').getHeight()-250;

    function showShortDesc()
    {
        tCKE2.resize( "100%", (total_height*60/100), true );
        $("#container_short_description").show();
    }
    function hideShortDesc()
    {
        $("#container_short_description").hide();
        tCKE2.resize( "100%", (total_height*1+100), true );
    }

    function putInBase()
    {
        $("#base_short_description").val(tCKE1.getData());
        $("#base_description").val(tCKE2.getData());
    }
</script>
<form id="form_descriptions" method="POST">
    <input name="ajax" type="hidden" value="1"/>
    <input name="act" type="hidden" value="man_description_update"/>
    <input id="id_manufacturer" name="id_manufacturer" type="hidden" value="<?php echo $id_manufacturer; ?>"/>
    <input id="id_lang" name="id_lang" type="hidden" value="<?php echo $id_lang; ?>"/>
    <div id="container_short_description">
        <textarea id="short_description" name="short_description" rows="10" style="width: 100%; height: 100%;"><?php echo $descriptions['short_description']; ?></textarea>
    </div>
    <div id="container_description">
        <textarea id="description" name="description" rows="30" style="width: 100%"><?php echo $descriptions['description']; ?></textarea>
    </div>

    <textarea id="base_short_description" rows="10" style="display:none;"><?php echo $descriptions['short_description']; ?></textarea>
    <textarea id="base_description" rows="30" style="display:none;"><?php echo $descriptions['description']; ?></textarea>

</form>
</body>
</html>
