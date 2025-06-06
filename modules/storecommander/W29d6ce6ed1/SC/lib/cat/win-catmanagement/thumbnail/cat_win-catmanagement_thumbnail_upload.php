<?php
if (!defined('STORE_COMMANDER')) { exit; }

if (!empty($_FILES))
{
    $ids = (array_key_exists('ids', $_REQUEST) ? (string) $_REQUEST['ids'] : null);
    $id_lang = (array_key_exists('id_lang', $_REQUEST) ? (int) $_REQUEST['id_lang'] : Configuration::get('PS_LANG_DEFAULT'));
    $action = (array_key_exists('action', $_REQUEST) ? (string) $_REQUEST['action'] : null);
    if (!empty($ids) && !empty($action))
    {
        switch ($action) {
            case 'upload':
                $folder = _PS_CAT_IMG_DIR_;
                $filename = uniqid();
                if (move_uploaded_file($_FILES['file']['tmp_name'], $folder.$filename))
                {
                    $image_filename = $folder.$filename;

                    $ids = explode(',', $ids);
                    foreach ($ids as $id_category)
                    {
                        $image_name = _PS_CAT_IMG_DIR_.(int) $id_category.'_thumb.jpg';
                        @unlink($image_name);
                        if (copy($image_filename, $image_name))
                        {
                            $images_types = ImageType::getImagesTypes('categories');
                            foreach ($images_types as $k => $image_type)
                            {
                                if (version_compare(_PS_VERSION_, '1.7.0.0', '>='))
                                {
                                    $formatted_small = ImageType::getFormattedName('small');
                                    if ($formatted_small == $image_type['name'])
                                    {
                                        $infos = getimagesize($image_filename);
                                        if (!empty($infos) && is_array($infos))
                                        {
                                            ImageManager::resize(
                                                $image_filename,
                                                _PS_CAT_IMG_DIR_.$id_category.'_thumb.jpg',
                                                (int) $infos[0],
                                                (int) $infos[1]
                                            );
                                        }
                                    }
                                }
                                elseif (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
                                {
                                    if (strpos($image_type['name'], 'medium') !== false)
                                    {
                                        if (!ImageManager::resize(
                                            $image_name,
                                            _PS_CAT_IMG_DIR_.$id_category.'-'.stripslashes($image_type['name']).'.jpg',
                                            (int) $image_type['width'],
                                            (int) $image_type['height']
                                        ))
                                        {
                                            $infos = getimagesize($image_name);
                                            if (!empty($infos) && is_array($infos))
                                            {
                                                ImageManager::resize(
                                                    $image_name,
                                                    _PS_CAT_IMG_DIR_.$id_category.'_thumb.jpg',
                                                    (int) $infos[0],
                                                    (int) $infos[1]
                                                );
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    if (strpos($image_type['name'], 'medium') !== false)
                                    {
                                        imageResize($image_name, _PS_CAT_IMG_DIR_.$id_category.'-'.stripslashes($image_type['name']).'.jpg', (int) ($image_type['width']), (int) ($image_type['height']));
                                    }
                                }
                            }
                            $success = true;
                        }
                    }
                }
                else
                {
                    exit('{"jsonrpc" : "2.0", "result" : null, "error" : {"code": 108, "message": "An error occurred during file upload. Please try again."}, "id" : "id"}');
                }
                break;
        }
    }
}
else
{
    ?>
<style type="text/css">@import url(<?php echo SC_PLUPLOAD; ?>js/vault/vault.min.css);</style>
    <script type="text/javascript" src="<?php echo SC_JQUERY; ?>"></script>
    <script type="text/javascript" src="<?php echo SC_JSFUNCTIONS; ?>"></script>
    <script type="text/javascript" src="<?php echo SC_PLUPLOAD; ?>js/vault/vault.min.js"></script>
    <?php
    $id_lang = (int) Tools::getValue('id_lang', Configuration::get('PS_LANG_DEFAULT'));
    $ids = Tools::getValue('ids', 0);
    if (!empty($ids))
    {
        ?>
    <body style="margin:0;">
    <div id="file_uploader"></div>
    <script type="text/javascript">
        <?php require_once SC_PLUPLOAD.'js/vault/vault_lang.php'; ?>
        let authorized_extensions = <?php echo json_encode(SCI::getAllowedFileExtension('image')); ?>;
        let vaultObject = new dhx.Vault("file_uploader", {
            uploader: {
                target: 'index.php?ajax=1&act=cat_win-catmanagement_thumbnail_upload&ids=<?php echo $ids; ?>&id_lang=<?php echo (int) $id_lang; ?>&action=upload',
                autosend: false
            },
            mode:"grid",
        });
        vaultObject.events.on("beforeAdd", function (item) {
            let extension = item.file.name.split('.').pop();
            if (vaultObject.data.getLength() >= 1) {
                dhx.message({
                    text: "<?php echo _l('Only one file by upload', 1); ?>",
                    css: "dhx-error",
                    expire: 4000
                });
                return false;
            }

            if (authorized_extensions.indexOf(extension.toLowerCase()) < 0) {
                dhx.message({
                    text: "<?php echo _l('Wrong file format', 1); ?> (" + authorized_extensions.join(',') + " <?php echo _l('only', 1); ?>)",
                    css: "dhx-error",
                    expire: 4000
                });
                return false;
            }

            let fileSize = item.file.size;
            let fileSizeMo = fileSize / 1024 / 1024;
            <?php
            if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
            {
                $limitSizeMo = (Configuration::get('PS_LIMIT_UPLOAD_IMAGE_VALUE'));
            }
        else
        {
            $limitSizeMo = (Configuration::get('PS_PRODUCT_PICTURE_MAX_SIZE') / 1024 / 1024);
        } ?>
            let limitSize = <?php echo $limitSizeMo; ?>;
            if (fileSizeMo > limitSize) {
                dhx.message({
                    text: "<?php echo _l('The file is too large. Maximum size allowed is: %1$d Mo. The file you are trying to upload is ', 1, array($limitSizeMo)); ?> " + fileSizeMo.toFixed(2) + " Mo",
                    css: "dhx-error",
                    expire: 4000
                });
                return false;
            }
        });
        vaultObject.events.on("UploadComplete", function (files) {
            var error = 0;
            files.forEach(function (item) {
                if (item.request.response.length > 0) {
                    let file_response = JSON.parse(item.request.response);
                    if (file_response.error !== null) {
                        dhx.message({
                            text: "code:" + file_response.error.code + " " + file_response.error.message,
                            css: "dhx-error",
                            expire: 4000
                        });
                        error = error + 1;
                    }
                }
            });
            if (error === 0) {
                parent.getCatManagementPropThumbnail();
                parent.cat_prop_thumbnail.cells('b').collapse();
            }
        });
        vaultObject.events.on("UploadFail", function (file) {
            dhx.message({
                text: "<?php echo _l('Error', 1); ?>",
                css: "dhx-error",
                expire: 4000
            });
        });
    </script>
    </body>
        <?php
    }
}
