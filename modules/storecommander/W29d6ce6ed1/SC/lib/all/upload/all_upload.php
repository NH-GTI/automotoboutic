<?php
if (!defined('STORE_COMMANDER')) { exit; }

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

if (!Tools::isSubmit('obj')
    || empty($_FILES)
    || !array_key_exists('file', $_FILES)
    || empty($_FILES['file']))
{
    exitResponseJson(99, _l('Something wrong with file data'));
}

## regroup $_FILES (simple/multi) into simple array
$fileList = array();
foreach ($_FILES['file'] as $data_type => $data)
{
    if (is_array($data)) ## if multi files
    {
        foreach ($data as $image_iteration => $value)
        {
            $fileList[$image_iteration][$data_type] = $value;
        }
    }
    else
    {
        $fileList[0][$data_type] = $data;
    }
}

// 5 minutes execution time
@set_time_limit(5 * 60);

$objectPs = Tools::getValue('obj');
foreach($fileList as $file)
{
    dofileProcess($objectPs, $file);
}

/**
 * @param $objectPs
 * @param $submittedFile $_FILES['file']
 * @return void
 */
function dofileProcess($objectPs, $submittedFile)
{
    global $languages;

    if (!file_exists($submittedFile['tmp_name']))
    {
        exitResponseJson(100, _l('Failed to retrieve tmp file'), $objectPs);
    }

    // Open temp file
    if (!isset($submittedFile['tmp_name'])
        || !is_uploaded_file($submittedFile['tmp_name'])
        || $submittedFile['error'] !== UPLOAD_ERR_OK
        || $submittedFile['size'] == 0
    )
    {
        exitResponseJson(101, _l('Failed to uploade file'), $objectPs);
    }

    $infoFile = pathinfo($submittedFile['name']);
    ## Clean the fileName for security reasons
    $fileName = preg_replace('/[^\w\._]+/', '', $infoFile['basename']);
    $fileExtension = $infoFile['extension'];

    switch ($objectPs) {
        case 'attrtexture':
            $targetDir = _PS_COL_IMG_DIR_;
            $id_attribute = (int) Tools::getValue('id_attribute');
            if(empty($id_attribute))
            {
                exitResponseJson(1011, _l('Invalid ID'), $objectPs);
            }
            $fileName = $id_attribute.'.jpg';
            break;
        case 'importcsv':
            $targetDir = SC_CSV_IMPORT_DIR;
            break;
        case 'importcsvcat':
            $targetDir = SC_CSV_IMPORT_DIR.'category/';
            break;
        case 'importcsvcus':
            $targetDir = SC_CSV_IMPORT_DIR.'customers/';
            break;
        case 'importcsvman':
            $targetDir = SC_CSV_IMPORT_DIR.'manufacturers/';
            break;
        case 'attachment':
        case 'productdownload':
            $targetDir = _PS_DOWNLOAD_DIR_;
            $submittedFile['basename'] = $fileName;
            do {
                $fileName = $submittedFile['name'] = sha1(microtime());
            } while (file_exists($targetDir . $submittedFile['name']));
            break;
        case 'mail_attachment':
            $formId = (int) Tools::getValue('formId');
            if(empty($formId))
            {
                exitResponseJson(1012, _l('Invalid folder'), $objectPs);
            }
            $targetDir = SC_MAIL_ATTACHMENT_DIR.$formId.DIRECTORY_SEPARATOR;
            break;
        case 'image':
            if (file_exists(SC_TOOLS_DIR.'lib/all/upload/upload-image.inc.php'))
            {
                require_once SC_TOOLS_DIR.'lib/all/upload/upload-image.inc.php';
            }
            else
            {
                require_once 'upload-image.inc.php';
            }
            $targetDir = _PS_TMP_IMG_DIR_;
            break;
        case 'manufacturer_logo':
            $targetDir = _PS_MANU_IMG_DIR_;
            break;
        case 'supplier_logo':
            $targetDir = _PS_SUPP_IMG_DIR_;
            break;
        default:
            $targetDir = null;
            exitResponseJson(1013, _l('Invalid item'), $objectPs);
    }

    ## Create target dir
    if (!file_exists($targetDir))
    {
        if(!mkdir($targetDir)) {
            exitResponseJson(102, _l('Unable to create directory').' '.$targetDir, $objectPs);
        }
    }

    ## contain forbidden extension?
    if(in_array($fileExtension, SCI::getForbiddenFileExtension('other')))
    {
        exitResponseJson(1021, _l('Cannot be added. For more information, please contact us'), $objectPs);
    }

    $tempFile = $submittedFile;
    if(in_array($objectPs, array('attachment', 'productdownload')))
    {
        $tempFile['name'] = $tempFile['basename'];
    }

    ## is image file?
    if(in_array($fileExtension, SCI::getAllowedFileExtension('image'))
        && !isImage($tempFile))
    {
        exitResponseJson(1022, _l('Not an image file'), $objectPs);
    }

    ## is csv file?
    if(in_array($fileExtension, SCI::getAllowedFileExtension('csv'))
        && !isCsv($tempFile))
    {
            exitResponseJson(1023, _l('Not a csv file'), $objectPs);
    }

    unset($tempFile);

    // Open temp file
    $out = fopen($targetDir.$fileName, 'wb');
    if (!$out)
    {
        exitResponseJson(103, _l('Failed to open output stream: %s<br/>This folder must be writeable', false, array(implode('<br/>', explode('/', $targetDir.$fileName)))), $objectPs);
    }

    // Read binary input stream and append it to temp file
    $in = fopen($submittedFile['tmp_name'], 'rb');
    if (!$in)
    {
        exitResponseJson(104, _l('Failed to open input stream'), $objectPs);
    }

    while ($buff = fread($in, 4096))
    {
        fwrite($out, $buff);
    }

    fclose($in);
    fclose($out);
    try {
        unlink($submittedFile['tmp_name']);
    } catch (Exception $e) {
        PrestaShopLogger::addLog('Sc unable to delete file : '.$submittedFile['tmp_name'], 1, null, null, null, true);
    } finally {
        insertIntoPs($targetDir, $objectPs, $submittedFile);
    }
}

/**
 * AJOUTER DANS PS
 * @param $targetDir
 * @param $objectPs
 * @param $submittedFile $submittedFile $_FILES['file']
 * @return void
 */
function insertIntoPs($targetDir, $objectPs, $submittedFile)
{
    global $languages;

    $generate_hight_dpi_images = (bool) SCI::getConfigurationValue('PS_HIGHT_DPI');
    $infoFile = pathinfo($submittedFile['name']);
    ## Clean the fileName for security reasons
    $fileName = preg_replace('/[^\w\._]+/', '', $infoFile['basename']);

    switch ($objectPs) {
        case 'attrtexture':
        case 'importcsv':
        case 'importcsvcus':
        case 'importcsvcat':
        case 'importcsvman':
            ## nothing to create
            break;
        case 'mail_attachment':
            $response = array(
                'state' => true,
                'name' => $fileName,
                'extra' => array(
                    'info' => $submittedFile,
                    'param' => '',
                ),
            );
            exitResponseJson(200,null, $objectPs, $response);
            break;
        case 'attachment':
            $name = $file_name = $submittedFile['basename']; ## using $submittedFile because of sha1
            $mime = getFileMime($targetDir.$submittedFile['name']);

            if(Tools::isSubmit('action'))
            {
                $action = Tools::getValue('action');
                switch($action)
                {
                    case 'edit_file':
                        $id_attachment = (int)Tools::getValue('id_attachment');
                        if($id_attachment)
                        {
                            $attachment = new Attachment((int)$id_attachment);
                            if(file_exists($targetDir.$attachment->file)
                                && !unlink($targetDir.$attachment->file)) ## delete old file
                            {
                                PrestaShopLogger::addLog('Sc unable to delete file : '.$targetDir.$attachment->file, 1, null, null, null, true);
                            }
                            $attachment->file = $submittedFile['name'];
                            $attachment->file_name = $file_name;
                            $attachment->mime = $mime;
                            if($attachment->save())
                            {
                                $nameLang = substr($file_name, 0, -4);
                                foreach ($languages as $lang)
                                {
                                    $desc = '';
                                    if (_s('CAT_PROD_ATTCH_DESC') == '1')
                                    {
                                        $desc = $nameLang.'_'.$lang['iso_code'];
                                    }
                                    elseif (_s('CAT_PROD_ATTCH_DESC') == '2')
                                    {
                                        $desc = $nameLang;
                                    }
                                    $sql = 'UPDATE `'._DB_PREFIX_.'attachment_lang` 
                                            SET `name` = "'.pSQL($nameLang).'", 
                                                `description` = "'.pSQL($desc).'" 
                                            WHERE `id_attachment` = '.(int)$id_attachment.' 
                                            AND id_lang = '.(int) $lang['id_lang'];
                                    Db::getInstance()->execute($sql);
                                }
                            }
                        }
                        break 2;
                }
            }
            $sql = 'INSERT INTO `'._DB_PREFIX_."attachment` (file,file_name,mime) VALUES ('".pSQL($fileName)."','".pSQL($file_name)."','".pSQL($mime)."')";
            Db::getInstance()->execute($sql);
            $id_attachment = Db::getInstance()->Insert_ID();
            $sqlstr = '';
            $name = substr($name, 0, -4);
            foreach ($languages as $lang)
            {
                $desc = '';
                if (_s('CAT_PROD_ATTCH_DESC') == '1')
                {
                    $desc = $name.'_'.$lang['iso_code'];
                }
                elseif (_s('CAT_PROD_ATTCH_DESC') == '2')
                {
                    $desc = $name;
                }
                $sqlstr .= '('.(int) $id_attachment.','.(int) $lang['id_lang'].',\''.pSQL($name).'\',\''.pSQL($desc).'\'),';
            }
            $sqlstr = trim($sqlstr, ',');
            $sql2 = 'INSERT INTO `'._DB_PREFIX_.'attachment_lang` (id_attachment,id_lang,name,description) VALUES '.$sqlstr;
            Db::getInstance()->execute($sql2);
            $linktoproduct = Tools::getValue('linktoproduct', '0');
            $product_list = Tools::getValue('product_list', 'null');
            if ($linktoproduct && $product_list != 'null')
            {
                $sql = 'DELETE FROM `'._DB_PREFIX_.'product_attachment` WHERE `id_attachment` = '.(int) $id_attachment.' AND `id_product` IN ('.pInSQL($product_list).')';
                Db::getInstance()->execute($sql);
                $sqlstr = array();
                $product_listarray = explode(',', $product_list);
                foreach ($product_listarray as $id_product)
                {
                    $sqlstr[] = '('.$id_product.','.$id_attachment.')';
                }
                $sqlstr = array_unique($sqlstr);
                $sql = 'INSERT INTO `'._DB_PREFIX_.'product_attachment` (id_product,id_attachment) VALUES '.pSQL(implode(',', $sqlstr));
                Db::getInstance()->execute($sql);

                $sql = 'UPDATE `'._DB_PREFIX_.'product` SET cache_has_attachments=1 WHERE `id_product` IN ('.pInSQL($product_list).')';
                Db::getInstance()->execute($sql);
            }
            if (version_compare(_PS_VERSION_, '1.6.0.0', '>='))
            {
                clearstatcache();
                $file_size = @filesize(_PS_DOWNLOAD_DIR_.$fileName);
                Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'attachment SET file_size = '.(int) $file_size.' WHERE id_attachment = '.(int) $id_attachment);
            }

            // PM Cache
            if (!empty($product_list))
            {
                ExtensionPMCM::clearFromIdsProduct($product_list);
            }
            break;
        case 'productdownload':
            if(Tools::isSubmit('action'))
            {
                $action = Tools::getValue('action');
                switch($action)
                {
                    case 'edit_file':
                        $id_product_download = (int)Tools::getValue('id_product_download');
                        if($id_product_download)
                        {
                            $download = new ProductDownload($id_product_download);
                            if(file_exists($targetDir.$download->filename)
                                && !unlink($targetDir.$download->filename)) ## delete old file
                            {
                                PrestaShopLogger::addLog('Sc unable to delete file : '.$targetDir.$download->filename, 1, null, null, null, true);
                            }
                            $download->display_filename = $submittedFile['basename'];
                            $download->filename = $submittedFile['name'];
                            if ($download->date_expiration == '0000-00-00 00:00:00')
                            {
                                $download->date_expiration = null;
                            }
                            $download->save();
                        }
                        break 2;
                }
            }

            $id_product = (int) Tools::getValue('id_product');
            $download = new ProductDownload();
            $download->id_product = (int) $id_product;
            $download->display_filename = $submittedFile['basename']; ## using $submittedFile because of sha1
            $download->filename = $submittedFile['name'];
            $download->date_add = date('Y-m-d H:i:s');
            $download->date_expiration = null;
            $download->nb_days_accessible = null;
            $download->nb_downloadable = null;
            $download->active = 1;
            if($download->save())
            {
                Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product 
                                                SET is_virtual=1'.(version_compare(_PS_VERSION_, '1.7.8.0', '>=') ? ', product_type="'.PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductType::TYPE_VIRTUAL.'"' : '').' 
                                                WHERE id_product = '.(int) $id_product);
            }

            break;
        case 'image':
            global $id_product,$id_image;
            $id_products = (Tools::getValue('product_list', 0));
            $attr_list = (Tools::getValue('attr_list', 0));
            $is_multiproduct = (Tools::getValue('is_multiproduct', 0));
            if ($is_multiproduct)
            {
                if (SCMS)
                {
                    $sql = 'SELECT id_product,id_product_attribute
                            FROM '._DB_PREFIX_.'product_attribute
                            WHERE id_product_attribute IN (SELECT id_product_attribute
                                                            FROM '._DB_PREFIX_.'product_attribute_shop
                                                            WHERE id_shop = '.(int) SCI::getSelectedShop().'
                                                            AND id_product_attribute IN ('.pInSQL($attr_list).'))';
                }
                else
                {
                    $sql = 'SELECT id_product,id_product_attribute
                        FROM '._DB_PREFIX_.'product_attribute
                        AND id_product_attribute IN ('.pInSQL($attr_list).')';
                }
                $res = Db::getInstance()->executeS($sql);
                $cache_product_attr = array();
                foreach ($res as $row)
                {
                    $cache_product_attr[$row['id_product']][] = (int) $row['id_product_attribute'];
                }
            }

            $tmpName = $targetDir.$fileName;

            $id_products = explode(',', $id_products);
            foreach ($id_products as $id_product)
            {
                $highPos = Image::getHighestPosition($id_product);
                $image = new Image();
                $image->id_product = $id_product;
                ++$highPos;
                $image->position = $highPos;
                $legends = array();
                foreach ($languages as $lang)
                {
                    if (SCMS)
                    {
                        $product = new Product($id_product, false, $lang['id_lang'], (int) SCI::getSelectedShop());
                    }
                    else
                    {
                        $product = new Product($id_product, false, $lang['id_lang']);
                    }
                    $n = explode('\.', $fileName);
                    array_pop($n);
                    $legends[$lang['id_lang']] = str_replace(array('#', '[', '^', '<', '>', '=', '{', '}', ']', '*', '  '), '', Tools::substr($product->name, 0, 128));
                    if(_s('CAT_PROD_IMG_DEFAULT_LEGEND')
                        && _s('CAT_PROD_IMG_DEFAULT_LEGEND') == 1
                        && $product->id_manufacturer){
                        $manufacturer = new Manufacturer($product->id_manufacturer, $lang['id_lang']);
                        $legends[$lang['id_lang']] .= ' '.$manufacturer->name;
                    }
                }
                $image->legend = $legends;
                if (version_compare(_PS_VERSION_, '1.6.0.0', '>='))
                {
                    if (!Image::getCover($id_product))
                    {
                        $image->cover = 1;
                    }
                    else
                    {
                        $image->cover = 0;
                    }
                }
                if (SCMS)
                {
                    $image->id_shop_list = SCI::getSelectedShopActionList(false, $id_product);
                }
                if (!$image->add())
                {
                    exitResponseJson(105, _l('Error creating image object'), $objectPs);
                }
                $id_image = $image->id;
                $ext = substr(Tools::strtolower($fileName), Tools::strlen(Tools::strtolower($fileName)) - 3, 3);
                $imagesTypes = ImageType::getImagesTypes('products');
                switch (_s('CAT_PROD_IMG_PNG_METHOD')){
                    case 0:
                        $newImageSourcePath = _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, '', 'jpg');
                        if (!copy($tmpName, $newImageSourcePath))
                        {
                            exitResponseJson(1051, _l('PS: An error occurred while copying image source'), $objectPs);
                        }

                        $tinypng = _s('CAT_PROD_IMG_TINYPNG');
                        if (!empty($tinypng))
                        {
                            require_once SC_DIR.'lib/php/tinypng/lib/Tinify/Exception.php';
                            require_once SC_DIR.'lib/php/tinypng/lib/Tinify/ResultMeta.php';
                            require_once SC_DIR.'lib/php/tinypng/lib/Tinify/Result.php';
                            require_once SC_DIR.'lib/php/tinypng/lib/Tinify/Source.php';
                            require_once SC_DIR.'lib/php/tinypng/lib/Tinify/Client.php';
                            require_once SC_DIR.'lib/php/tinypng/lib/Tinify.php';

                            try
                            {
                                \Tinify\setKey($tinypng);
                                \Tinify\validate();
                                $source = \Tinify\fromFile($newImageSourcePath);
                                $preservedMeta = $source->preserve('copyright', 'creation', 'location');
                                $preservedMeta->toFile($newImageSourcePath);
                            }
                            catch (Exception $e)
                            {
                            }
                        }

                        foreach ($imagesTypes as $k => $imageType)
                        {
                            if (!imageResize($newImageSourcePath, _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, stripslashes($imageType['name']), 'jpg'), $imageType['width'], $imageType['height'], 'jpg'))
                            {
                                exitResponseJson(1052, _l('PS: An error occurred while copying image %s', false, array(stripslashes($imageType['name']))), $objectPs);
                            }
                            else
                            {
                                if ($generate_hight_dpi_images)
                                {
                                    $name = _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, stripslashes($imageType['name']), 'jpg');
                                    $name = str_replace('.jpg', '2x.jpg', $name);
                                    imageResize($newImageSourcePath, $name, $imageType['width'] * 2, $imageType['height'] * 2, 'jpg');
                                }
                            }
                        }
                        break;
                    case 1:
                        if (!imageResize($tmpName, _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, '', 'jpg'), null, null, $ext))
                        {
                            exitResponseJson(1053, _l('PS: An error occurred while copying image'), $objectPs);
                        }
                        foreach ($imagesTypes as $k => $imageType)
                        {
                            if (!imageResize($tmpName, _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, stripslashes($imageType['name']), 'jpg'), $imageType['width'], $imageType['height'], $ext))
                            {
                                exitResponseJson(10531, _l('PS: An error occurred while copying image %s', false, array(stripslashes($imageType['name']))), $objectPs);
                            }
                            else
                            {
                                if ($generate_hight_dpi_images)
                                {
                                    $name = _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, stripslashes($imageType['name']), 'jpg');
                                    $name = str_replace('.jpg', '2x.jpg', $name);
                                    imageResize($tmpName, $name, $imageType['width'] * 2, $imageType['height'] * 2, $ext);
                                }
                            }
                        }
                        break;
                    case 2:
                        if ($ext == 'png' && !imageResize($tmpName, _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, '', 'png'), null, null, 'png'))
                        {
                            exitResponseJson(1054, _l('PS: An error occurred while copying image'), $objectPs);
                        }
                        if (!imageResize($tmpName, _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, '', 'jpg'), null, null, 'jpg'))
                        {
                            exitResponseJson(1055, _l('PS: An error occurred while copying image'), $objectPs);
                        }
                        foreach ($imagesTypes as $k => $imageType)
                        {
                            if ($ext == 'png' && !imageResize($tmpName, _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, stripslashes($imageType['name']), 'png'), $imageType['width'], $imageType['height'], 'png'))
                            {
                                exitResponseJson(10551, _l('PS: An error occurred while copying image %s', false, array(stripslashes($imageType['name']))), $objectPs);
                            }
                            if (!imageResize($tmpName, _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, stripslashes($imageType['name']), 'jpg'), $imageType['width'], $imageType['height'], 'jpg'))
                            {
                                exitResponseJson(10552, _l('PS: An error occurred while copying image %s', false, array(stripslashes($imageType['name']))), $objectPs);
                            }
                            else
                            {
                                if ($generate_hight_dpi_images)
                                {
                                    $name = _PS_IMG_DIR_.'p/'.getImgPath($id_product, $id_image, stripslashes($imageType['name']), 'jpg');
                                    $name = str_replace('.jpg', '2x.jpg', $name);
                                    imageResize($tmpName, $name, $imageType['width'] * 2, $imageType['height'] * 2, 'jpg');
                                }
                            }
                        }
                        break;
                }
                SCI::hookExec('watermark', array('id_image' => $id_image, 'id_product' => $id_product));

                if (!Image::getCover($image->id_product))
                {
                    $first_img = Db::getInstance()->getRow('
                            SELECT `id_image` FROM `'._DB_PREFIX_.'image`
                            WHERE `id_product` = '.(int) $image->id_product);
                    Db::getInstance()->execute('
                            UPDATE `'._DB_PREFIX_.'image`
                            SET `cover` = 1
                            WHERE `id_image` = '.(int) $first_img['id_image']);
                    if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
                    {
                        $sql = 'UPDATE `'._DB_PREFIX_.'image_shop` SET `cover` = 1 WHERE id_image='.(int) $first_img['id_image'].' AND id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true, $id_product)).')';
                        Db::getInstance()->execute($sql);
                    }
                }

                if (!empty($attr_list))
                {
                    if ($is_multiproduct)
                    {
                        $attr_list = $cache_product_attr[$id_product];
                    }
                    if (!is_array($attr_list))
                    {
                        $attr_list = explode(',', $attr_list);
                    }
                    foreach ($attr_list as $attr)
                    {
                        if (!empty($attr))
                        {
                            $sql = 'INSERT INTO `'._DB_PREFIX_."product_attribute_image` (id_product_attribute,id_image) VALUES ('".(int) $attr."','".(int) $id_image."')";
                            Db::getInstance()->execute($sql);
                        }
                    }
                }

                if (_s('CAT_PROD_IMG_SAVE_FILENAME'))
                {
                    $sql = 'UPDATE '._DB_PREFIX_."image SET sc_path='".pSQL($fileName)."' WHERE id_image = ".(int) $id_image;
                    Db::getInstance()->execute($sql);
                }
            }
            if(file_exists($tmpName)
                && !unlink($tmpName)) ## delete old file
            {
                PrestaShopLogger::addLog('Sc unable to delete file : '.$tmpName, 1, null, null, null, true);
            }

            // PM Cache
            if (!empty($id_products))
            {
                ExtensionPMCM::clearFromIdsProduct($id_products);
            }
            break;
        case 'manufacturer_logo':
            $manufacturer_list = (Tools::getValue('manufacturer_list', 0));
            $ids_manufacturer = explode(',', $manufacturer_list);
            $tmpName = $targetDir.$fileName;
            foreach ($ids_manufacturer as $id_manufacturer)
            {
                $newImageSourcePath = $targetDir.$id_manufacturer.'.jpg';
                if(file_exists($newImageSourcePath)
                    && !unlink($newImageSourcePath)) ## delete old file
                {
                    PrestaShopLogger::addLog('Sc unable to delete file : '.$newImageSourcePath, 1, null, null, null, true);
                }
                if (!copy($tmpName, $newImageSourcePath))
                {
                    exitResponseJson(106, _l('PS: An error occurred while copying image source'), $objectPs);
                }
                else
                {
                    $images_types = ImageType::getImagesTypes('manufacturers');
                    foreach ($images_types as $k => $image_type)
                    {
                        ImageManager::resize(
                            _PS_MANU_IMG_DIR_.$id_manufacturer.'.jpg',
                            _PS_MANU_IMG_DIR_.$id_manufacturer.'-'.stripslashes($image_type['name']).'.jpg',
                            (int) $image_type['width'],
                            (int) $image_type['height']
                        );

                        if ($generate_hight_dpi_images)
                        {
                            ImageManager::resize(
                                _PS_MANU_IMG_DIR_.$id_manufacturer.'.jpg',
                                _PS_MANU_IMG_DIR_.$id_manufacturer.'-'.stripslashes($image_type['name']).'2x.jpg',
                                (int) $image_type['width'] * 2,
                                (int) $image_type['height'] * 2
                            );
                        }
                    }
                }
            }
            if(file_exists($tmpName)
                && !unlink($tmpName)) ## delete old file
            {
                PrestaShopLogger::addLog('Sc unable to delete file : '.$tmpName, 1, null, null, null, true);
            }
            break;
        case 'supplier_logo':
            $supplier_list = (Tools::getValue('supplier_list', 0));
            $ids_supplier = explode(',', $supplier_list);
            $tmpName = $targetDir.$fileName;
            foreach ($ids_supplier as $id_supplier)
            {
                $newImageSourcePath = $targetDir.$id_supplier.'.jpg';
                if(file_exists($newImageSourcePath)
                    && !unlink($newImageSourcePath)) ## delete old file
                {
                    PrestaShopLogger::addLog('Sc unable to delete file : '.$newImageSourcePath, 1, null, null, null, true);
                }
                if (!copy($tmpName, $newImageSourcePath))
                {
                    exitResponseJson(107, _l('PS: An error occurred while copying image source'), $objectPs);
                }
                else
                {
                    $images_types = ImageType::getImagesTypes('suppliers');
                    foreach ($images_types as $k => $image_type)
                    {
                        ImageManager::resize(
                            _PS_SUPP_IMG_DIR_.$id_supplier.'.jpg',
                            _PS_SUPP_IMG_DIR_.$id_supplier.'-'.stripslashes($image_type['name']).'.jpg',
                            (int) $image_type['width'],
                            (int) $image_type['height']
                        );

                        if ($generate_hight_dpi_images)
                        {
                            ImageManager::resize(
                                _PS_SUPP_IMG_DIR_.$id_supplier.'.jpg',
                                _PS_SUPP_IMG_DIR_.$id_supplier.'-'.stripslashes($image_type['name']).'2x.jpg',
                                (int) $image_type['width'] * 2,
                                (int) $image_type['height'] * 2
                            );
                        }
                    }
                }
            }
            if(file_exists($tmpName)
                && !unlink($tmpName)) ## delete old file
            {
                PrestaShopLogger::addLog('Sc unable to delete file : '.$tmpName, 1, null, null, null, true);
            }
            break;
        default:
            exitResponseJson(108, _l('Failed to create PS object'), $objectPs);
    }
    $products = Tools::getValue('product_list', 'null');
    if (!empty($products))
    {
        Db::getInstance()->execute('UPDATE '._DB_PREFIX_."product SET date_upd = '".pSQL(date('Y-m-d H:i:s'))."' WHERE id_product IN (".pInSQL($products).')');
        if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
        {
            Db::getInstance()->execute('UPDATE '._DB_PREFIX_."product_shop SET date_upd = '".pSQL(date('Y-m-d H:i:s'))."' WHERE id_product IN (".pInSQL($products).') AND id_shop IN ('.pInSQL(SCI::getSelectedShopActionList(true)).')');
        }
    }
}

/**
 * @param $code
 * @param $message
 * @param $objectPs
 * @param $customResponse
 * @return void
 */
function exitResponseJson($code = null, $message = null, $objectPs=null, $customResponse = null)
{
    switch ($objectPs)
    {
        case 'mail_attachment':
            if($code !== 200)
            {
                $response = array(
                    'state' => false,
                    'extra' => array(
                        'code' => $code,
                        'message' => $message,
                    ),
                    'id' => 'id',
                );
            } else {
                $response = $customResponse;
            }
            break;
        default:
            $response = array(
                'jsonrpc' => '2.0',
                'result' =>  '',
                'error' => null,
                'id' => '#ID#'
            );
            if($code)
            {
                $response['error'] = array(
                    'code' => (int)$code
                );
                if($message)
                {
                    $response['error']['message'] = $message;
                }
            }
    }

    exit(json_encode($response));
}

exitResponseJson();