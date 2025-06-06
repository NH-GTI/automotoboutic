<?php
if (!defined('STORE_COMMANDER')) { exit; }

    $id_lang = (int) Tools::getValue('id_lang');
    $id_product = (int) Tools::getValue('id_product', 0);
    $descriptions = array('description_short' => '', 'description' => '');
    if ($id_product != 0)
    {
        if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
        {
            $id_shop = SCI::getSelectedShop();
            if (empty($id_shop))
            {
                $product = new Product($id_product);
                $id_shop = $product->id_shop_default;
            }
        }

        $sql = 'SELECT description_short,description FROM '._DB_PREFIX_."product_lang WHERE id_product=" .(int) $id_product . " AND id_lang=" .(int) $id_lang ;
        if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
        {
            $sql .= ' AND id_shop='.(int) $id_shop;
        }
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
    if (file_exists(SC_TOOLS_DIR.'cat_description/tiny_config.php'))
    {
        require_once SC_TOOLS_DIR.'cat_description/tiny_config.php';
    }
    else
    {
        require_once 'tiny_config.php';
    }
?>
    });

function checkSizetMCE() {
    <?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>
    var tiny=$('#description_short').tinymce();
    window.top.prop_tb.setItemText('txt_descriptionsize','<?php echo _l('Short description charset', 1)._l(':'); ?> '+tiny.getContent().length+'/<?php echo _s('CAT_SHORT_DESC_SIZE'); ?>');
    <?php } ?>
    return true;
}
function checkSize() {
    <?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>
    var tiny=$('#description_short').tinymce();
    if (tiny.getContent().replace(/<[^>]+>/g, '').length <= <?php echo _s('CAT_SHORT_DESC_SIZE'); ?>) return true;
    <?php }
else
{ ?>
    return true
    <?php } ?>
    return false;
}
var tMCE1=0;
var tMCE2=0;
var tMCE1Content=0;
var tMCE2Content=0;

function ajaxLoad(args,id_product,id_lang) {
    <?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>
    if (tMCE1==0) tMCE1 = $('#description_short').tinymce();
    <?php } ?>
    if (tMCE2==0) tMCE2 = $('#description').tinymce();
    $('#id_product').val(id_product);
    $('#id_lang').val(id_lang);
    <?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>
    tMCE1.setProgressState(1);
    $.get("index.php?ajax=1&act=cat_description_get&content=description_short"+args, function(data){
        tMCE1.setProgressState(0);
        tMCE1.setContent(data);
        tMCE1Content=data;
        tMCE1.isNotDirty=1; // change modified state of tinyMCE
        checkSizetMCE();
    });
    <?php } ?>
    tMCE2.setProgressState(1);
    $.get("index.php?ajax=1&act=cat_description_get&content=description"+args, function(data){
        tMCE2.setProgressState(0);
        tMCE2.setContent(data);
        tMCE2Content=data;
        tMCE2.isNotDirty=1; // change modified state of tinyMCE
        });
}
function ajaxSave() {
    <?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>
    if (tMCE1==0) tMCE1 = $('#description_short').tinymce();
    tMCE1.setProgressState(1);
    <?php } ?>
    if (tMCE2==0) tMCE2 = $('#description').tinymce();
    tMCE2.setProgressState(1);
    $.post("index.php", $("#form_descriptions").serialize(), function(data){
            <?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>
            tMCE1.setProgressState(0);
            <?php } ?>
            tMCE2.setProgressState(0);
            if (data=='OK')
            {
                <?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>
                tMCE1.isNotDirty=1;
                <?php } ?>
                tMCE2.isNotDirty=1;
            }else{
                if (data=='ERR|description_short_size')
                {
                    alert('<?php echo _l('Short description size must be < ', 1)._s('CAT_SHORT_DESC_SIZE'); ?>');
                }
                <?php if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) { ?>
                if (data=='ERR|description_short_with_iframe')
                {
                    alert('<?php echo _l('Short description can\'t include an iframe or is invalid', 1); ?>');
                }
                if (data=='ERR|description_with_iframe')
                {
                    alert('<?php echo _l('Description can\'t include an iframe or is invalid', 1); ?>');
                }
                if (data=='ERR|description_short_invalid')
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
    <?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>
    if (tMCE1==0) tMCE1 = $('#description_short').tinymce();
    <?php } ?>
    if (tMCE2==0) tMCE2 = $('#description').tinymce();
    <?php if (_s('CAT_NOTICE_SAVE_DESCRIPTION')) { ?>
    if (<?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>tMCE1.isDirty() || <?php } ?>tMCE2.isDirty())
       if (confirm('<?php echo _l('Do you want to save the descriptions?', 1); ?>'))
           ajaxSave();
    <?php } ?>
}


function showShortDesc()
{
    $("#container_description_short").show();
}
function hideShortDesc()
{
    $("#container_description_short").hide();
}
</script>
<form id="form_descriptions" method="POST">
<input name="ajax" type="hidden" value="1"/>
<input name="act" type="hidden" value="cat_description_update"/>
<input id="id_product" name="id_product" type="hidden" value="<?php echo $id_product; ?>"/>
<input id="id_lang" name="id_lang" type="hidden" value="<?php echo $id_lang; ?>"/>
<?php if (_r('INT_CAT_PROPERTIES_DESC_SHOW_DESC_SHORT')) { ?>
<div id="container_description_short">
<textarea id="description_short" name="description_short" class="tinymce1 rte" cols="50" rows="10" style=""><?php echo $descriptions['description_short']; ?></textarea>
</div>
<?php } ?>
<textarea id="description" name="description" class="tinymce2 rte" cols="50" rows="30" style=""><?php echo $descriptions['description']; ?></textarea>
</form>
</body>
</html>