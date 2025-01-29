<?php
if (!defined('STORE_COMMANDER')) { exit; }

$id_product = (int) Tools::getValue('id_product', 0);
$id_product_download = (int) Tools::getValue('id_product_download', 0);

$urlParams = array(
    'id_product' => $id_product
);
if (!empty($id_product_download))
{
    $urlParams = array(
        'id_product_download' => $id_product_download,
        'action' => 'edit_file'
    );
}
$url = http_build_query($urlParams);

$error_uploadable = array();
if($id_product)
{
    $product = new Product($id_product, false, null, (int) SCI::getSelectedShop());
    if ($product->hasAttributes())
    {
        $error_uploadable[] = _l('A virtual product cannot have combinations.');
    }
    if (version_compare(_PS_VERSION_, '1.7', '<'))
    {
        if ($product->advanced_stock_management)
        {
            $error_uploadable[] = _l('A virtual product cannot use the advanced stock management.');
        }
    }
}

$size_limit = ini_get('upload_max_filesize');
$size_limit = Tools::getOctets($size_limit);
$size_limit = $size_limit / 1024 / 1024;

if ($id_product || $id_product_download)
{
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <style>
        @import url(<?php echo SC_PLUPLOAD; ?>js/vault/vault.min.css);
        @import url(<?php echo SC_PLUPLOAD; ?>js/vault/vault.custom.css);
    </style>
    <script src="<?php echo SC_JQUERY; ?>"></script>
    <script src="<?php echo SC_JSFUNCTIONS; ?>"></script>
    <script src="<?php echo SC_PLUPLOAD; ?>js/vault/vault.min.js"></script>
</head>
<body>
<div id="file_uploader"></div>
<script>
<?php if(!empty($error_uploadable)) { ?>
    let check_product = <?php echo json_encode($error_uploadable); ?>;
    check_product.forEach(function (message) {
        dhx.message({
            text: message,
            css: "dhx-error",
            expire: 4000
        });
    });
<?php } else { ?>
    <?php require_once SC_PLUPLOAD.'js/vault/vault_lang.php'; ?>
    let forbidden_extensions = <?php echo json_encode(SCI::getForbiddenFileExtension('other')); ?>;
    let vaultObject = new dhx.Vault("file_uploader", {
        uploader:{
            target: 'index.php?ajax=1&act=all_upload&obj=productdownload&<?php echo $url?>',
            autosend:false
        }
    });

    vaultObject.events.on("UploadComplete", function(files){
        var error = 0;
        files.forEach(function(item){
            let file_response  = JSON.parse(item.request.response);
            if(file_response.error !== null) {
                vaultObject.data.update(item.id,{status:'failed'});
                dhx.message({
                    text: "code:"+file_response.error.code+" "+file_response.error.message,
                    css: "dhx-error",
                    expire: 4000
                });
                error = error+1;
            }
        });
        if(error === 0) {
            parent.displayProductDownload();
            parent.prop_tb._productdownloadLayout.cells('b').collapse();
        }
    });

    vaultObject.events.on("BeforeAdd", function(item) {
        this.uploader.config.autosend = false;
        this.uploader.autosend = false;
        this.paint();
        let extension = item.file.name.split('.').pop();
        if(forbidden_extensions.indexOf(extension.toLowerCase()) >= 0) {
            dhx.message({
                text: "<?php echo _l('Cannot be added. For more information, please contact us', 1); ?>",
                css: "dhx-error",
                expire: 4000
            });
            return false;
        }

        if (vaultObject.data.getLength() >= 1) {
            dhx.message({
                text: "<?php echo _l('Only one file by upload', 1); ?>",
                css: "dhx-error",
                expire: 4000
            });
            return false;
        }

        let size = item.file.size;
        let size_kb = Number(priceFormat(size / 1024 / 1024));
        let sizeLimit = <?php echo $size_limit; ?>;
        if (!size_kb > sizeLimit) {
            dhx.message({
                text: "<?php echo _l('The file is too large. Maximum size allowed is: %1$d Mo. The file you are trying to upload is ', 1, array(number_format($size_limit, 2, '.', ''))); ?> " + size_kb + " Mo",
                css: "dhx-error",
                expire: 4000
            });
            return false;
        }

        return true;
    });

    vaultObject.events.on("UploadFail", function(file){
        dhx.message({
            text: "<?php echo _l('Error', 1); ?>",
            css: "dhx-error",
            expire: 4000
        });
    });
<?php } ?>
</script>
</body>
</html>
<?php
} ?>