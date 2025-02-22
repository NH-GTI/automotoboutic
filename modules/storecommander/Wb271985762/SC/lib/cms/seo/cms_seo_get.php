<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

$id_lang = (int) Tools::getValue('id_lang');
$idlist = Tools::getValue('idlist');

function getRowsFromDB()
{
    global $idlist;

    if (!$idlist)
    {
        return;
    }

    $array_langs = [];
    $langs = Language::getLanguages(false);
    foreach ($langs as $lang)
    {
        $array_langs[$lang['id_lang']] = strtoupper($lang['iso_code']);
    }

    if (SCMS)
    {
        $array_shops = [];
        $shops = Shop::getShops(false);
        foreach ($shops as $shop)
        {
            $shop['name'] = str_replace('&', _l('and'), $shop['name']);
            $array_shops[$shop['id_shop']] = $shop['name'];
        }
    }

    $sql = (new DbQuery())
        ->select('cl.*, cs.`id_shop`')
        ->from('cms_lang', 'cl')
        ->innerJoin('cms_shop', 'cs', 'cs.`id_cms` = cl.`id_cms` AND cl.`id_shop` = '.(int) SCI::getSelectedShop())
        ->where('cl.`id_cms` IN ('.pInSQL($idlist).')')
        ->orderBy('cl.`id_cms`, cl.`id_lang`, cs.`id_shop`')
    ;
    if (!_s('CMS_PAGE_LANGUAGE_ALL'))
    {
        $sql->innerJoin('lang', 'l', 'cl.id_lang = l.id_lang AND l.active = 1');
    }
    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    $xml = '';
    if (!$res)
    {
        return $xml;
    }
    foreach ($res as $row)
    {
        $url = getUrl($row['id_cms'], $row['id_lang'], (SCMS ? $row['id_shop'] : '0'));

        $xml .= "<row id='".$row['id_cms'].'_'.$row['id_lang'].(SCMS ? '_'.$row['id_shop'] : '')."'>";
        $xml .= '<userdata name="url"><![CDATA['.$url.']]></userdata>';
        $xml .= '<cell>'.$row['id_cms'].'</cell>';
        if (SCMS)
        {
            $xml .= '<cell>'.$array_shops[$row['id_shop']].'</cell>';
        }
        $xml .= '<cell>'.$array_langs[$row['id_lang']].'</cell>';
        $xml .= '<cell><![CDATA['.$row['link_rewrite'].']]></cell>';
        if (version_compare(_PS_VERSION_, '1.7.5.0', '>='))
        {
            $xml .= '<cell><![CDATA['.$row['head_seo_title'].']]></cell>';
        }
        $meta_title_length = (int) strlen($row['meta_title']);
        if ($meta_title_length >= _s('CMS_SEO_META_TITLE_COLOR'))
        {
            $xml .= "<cell style='background-color: #FE9730'><![CDATA[".$row['meta_title'].']]></cell>';
        }
        elseif ($meta_title_length > 0 && $meta_title_length < _s('CMS_SEO_META_TITLE_COLOR_MIN'))
        {
            $xml .= "<cell style='background-color: #a7e1f7'><![CDATA[".$row['meta_title'].']]></cell>';
        }
        else
        {
            $xml .= '<cell><![CDATA['.$row['meta_title'].']]></cell>';
        }
        $xml .= '<cell><![CDATA['.strlen($row['meta_title']).']]></cell>';
        $xml .= '<cell><![CDATA['.$row['meta_description'].']]></cell>';
        $xml .= '<cell><![CDATA['.strlen($row['meta_description']).']]></cell>';
        $xml .= '<cell><![CDATA['.$row['meta_keywords'].']]></cell>';
        $xml .= '<cell><![CDATA['.strlen($row['meta_keywords']).']]></cell>';
        $xml .= '</row>';
    }

    return $xml;
}

$cache_cms = [];
$link = new Link();
function getUrl($id_cms, $id_lang, $id_shop = 0)
{
    global $cache_cms,$link;

    if (empty($cache_cms[$id_cms.'_'.$id_shop]))
    {
        if (SCMS)
        {
            $cache_cms[$id_cms] = new CMS((int) $id_cms, null, (int) $id_shop);
        }
        else
        {
            $cache_cms[$id_cms] = new CMS((int) $id_cms, null);
        }
    }
    $cms = $cache_cms[$id_cms];

    $alias = $cms->link_rewrite[$id_lang];

    if (SCMS)
    {
        return $link->getCMSLink($cms, $alias, null, $id_lang, (int) $id_shop);
    }

    if (!defined('_PS_BASE_URL_'))
    {
        define('_PS_BASE_URL_', Tools::getShopDomain(true));
    }

    return $link->getCMSLink($cms, $alias, null, $id_lang, (int) $id_shop);
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
<call command="attachHeader"><param><![CDATA[#text_filter<?php if (SCMS){ ?>,#select_filter<?php } ?>,#select_filter,#text_filter,<?php if (version_compare(_PS_VERSION_, '1.7.5.0', '>=')){ ?>#text_filter,<?php } ?>#text_filter,#text_filter,#text_filter,#text_filter,#text_filter,#text_filter]]></param></call>
</beforeInit>
<column id="id_cms" width="40" type="ro" align="right" sort="int"><?php echo _l('ID'); ?></column>
<?php if (SCMS){ ?>
<column id="shop" width="100" type="ro" align="left" sort="int"><?php echo _l('Shop'); ?></column>
<?php } ?>
<column id="lang" width="60" type="ro" align="center" sort="str"><?php echo _l('Lang'); ?></column>
<column id="link_rewrite" width="120" type="ed" align="left" sort="str"><?php echo _l('Link rewrite'); ?></column>
<?php if (version_compare(_PS_VERSION_, '1.7.5.0', '>=')) { ?>
<column id="head_seo_title" width="120" type="ed" align="left" sort="str"><?php echo _l('Head SEO title'); ?></column>
<?php } ?>
<column id="meta_title" width="120" type="ed" align="left" sort="str"><?php echo _l('META title'); ?></column>
<column id="meta_title_width" width="40" type="ro" align="right" sort="str"><?php echo _l('META title length'); ?></column>
<column id="meta_description" width="200" type="ed" align="left" sort="str"><?php echo _l('META description'); ?></column>
<column id="meta_description_width" width="40" type="ro" align="right" sort="str"><?php echo _l('META description length'); ?></column>
<column id="meta_keywords" width="120" type="ed" align="left" sort="str"><?php echo _l('META keywords'); ?></column>
<column id="meta_keywords_width" width="40" type="ro" align="right" sort="str"><?php echo _l('META keywords length'); ?></column>
<afterInit>
<call command="enableMultiselect"><param>1</param></call>
</afterInit>
</head>
<?php
    echo '<userdata name="uisettings">'.UISettings::getSetting('cms_CmsSeo').'</userdata>'."\n";
    echo $xml;
?>
</rows>
