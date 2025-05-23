<?php
if (!defined('STORE_COMMANDER')) { exit; }

    $id_lang = (int) Tools::getValue('id_lang');
    $idlist = (Tools::getValue('idlist', 0));

    function getRowsFromDB()
    {
        global $id_lang,$idlist;

        $ids = explode(',', $idlist);
        $xml = '';
        foreach ($ids as $id_category)
        {
            $filename = '/'.(int) $id_category.'.jpg';
            $image = '';
            if (file_exists(_PS_CAT_IMG_DIR_.$filename))
            {
                $image = '<img src="'.SC_PS_PATH_REL.'img/c/'.$filename.'?'.filemtime(_PS_CAT_IMG_DIR_.$filename).'" height="120px" alt="" />';
            }

            if (!empty($image))
            {
                $xml .= "<row id='".$id_category."'>";
                $xml .= '<cell>'.$id_category.'</cell>';
                $xml .= '<cell><![CDATA['.$image.']]></cell>';
                $xml .= '</row>';
            }
        }

        return $xml;
    }

    //XML HEADER
    if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
    {
        header('Content-type: application/xhtml+xml');
    }
    else
    {
        header('Content-type: text/xml');
    }
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml = '';
    if (!empty($idlist))
    {
        $xml = getRowsFromDB();
    }
?>
<rows id="0">
<head>
<beforeInit>
<call command="attachHeader"><param><![CDATA[#text_filter]]></param></call>
</beforeInit>
<column id="id_category" width="100" type="ro" align="right" sort="int"><?php echo _l('ID'); ?></column>
<column id="image" width="500" type="ro" align="center" sort="int"><?php echo _l('Image'); ?></column>
<afterInit>
<call command="enableMultiselect"><param>1</param></call>
</afterInit>
</head>
<?php
    echo '<userdata name="uisettings">'.uisettings::getSetting('cat_prop_image_grid').'</userdata>'."\n";
    echo $xml;
?>
</rows>
