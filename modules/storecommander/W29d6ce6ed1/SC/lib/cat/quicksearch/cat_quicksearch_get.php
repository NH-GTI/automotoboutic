<?php
if (!defined('STORE_COMMANDER')) { exit; }

$id_product = Tools::getValue('id_product', 1);
$id_product_attribute = Tools::getValue('id_product_attribute', 1);
$name = Tools::getValue('name', 1);
$reference = Tools::getValue('reference', 1);
$supplier_reference = Tools::getValue('supplier_reference', 1);
$supplier_reference_all = Tools::getValue('supplier_reference_all', 1);
$ean = Tools::getValue('ean', 1);
$upc = 0;
$upc = Tools::getValue('upc', 1);
$mpn = Tools::getValue('mpn', 1);
$short_desc = Tools::getValue('short_desc', 0);
$desc = Tools::getValue('desc', 0);
$how_equal = Tools::getValue('how_equal', 0);

if(!Tools::isSubmit('q'))
{
    exit;
}

$query = Tools::getValue('q');
$limit = 25 * $nblanguages;
$res = '';

if (SCI::getSelectedShop() > 0)
{
    $shop_where = (int) SCI::getSelectedShop();
}
else
{
    $shop_where = ' p.id_shop_default ';
}

if (is_numeric($query))
{
    $sql = 'SELECT p.id_product,p.id_category_default,pl.name as pname,cl.name as cname,pl2.name as pname2,pa.id_product_attribute, ps.id_category_default
            FROM `'._DB_PREFIX_.'product` p
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.id_product=pl.id_product AND pl.id_shop='.pSQL($shop_where).')
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl2 ON (p.id_product=pl2.id_product AND pl2.id_lang='.(int) $sc_agent->id_lang.' AND pl2.id_shop='.pSQL($shop_where).')
            LEFT JOIN `'._DB_PREFIX_.'product_shop` ps ON (ps.id_product = p.id_product AND ps.id_shop='.pSQL($shop_where).') 
            LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.id_product=pa.id_product)
            LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` pas ON (pas.id_product_attribute = pa.id_product_attribute AND pas.id_shop='.pSQL($shop_where).') 
            LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (cl.id_category=ps.id_category_default AND cl.id_lang='.(int) $sc_agent->id_lang.')
            '.($supplier_reference_all == 1 ? ' LEFT JOIN `'._DB_PREFIX_.'product_supplier` psup ON (psup.id_product=p.id_product) ' : '').'
            WHERE (0
                '.(($id_product == 1) ? " OR p.id_product = '".(float) $query."'" : '').'
                '.(($id_product_attribute == 1) ? " OR pa.id_product_attribute = '".(float) $query."'" : '').'
                '.(($ean == 1) ? ($how_equal == 1 ? " OR p.ean13 = '".pSQL($query)."'" : " OR p.ean13 LIKE '%".pSQL($query)."%'") : '').'
                '.(($ean == 1) ? ($how_equal == 1 ? " OR pa.ean13 = '".pSQL($query)."'" : " OR pa.ean13 LIKE '%".pSQL($query)."%'") : '').'
                '.(($reference == 1) ? ($how_equal == 1 ? " OR p.reference = '".pSQL($query)."'" : " OR p.reference LIKE '%".pSQL($query)."%'") : '').'
                '.(($supplier_reference == 1) ? ($how_equal == 1 ? " OR p.supplier_reference = '".pSQL($query)."'" : " OR p.supplier_reference LIKE '%".pSQL($query)."%'") : '').'
                '.(($reference == 1) ? ($how_equal == 1 ? " OR pa.reference = '".pSQL($query)."'" : " OR pa.reference LIKE '%".pSQL($query)."%'") : '').'
                '.(($supplier_reference == 1) ? ($how_equal == 1 ? " OR pa.supplier_reference = '".pSQL($query)."'" : " OR pa.supplier_reference LIKE '%".pSQL($query)."%'") : '').'
                '.(($upc == 1) ? ($how_equal == 1 ? " OR p.upc = '".pSQL($query)."'" : " OR p.upc LIKE '%".pSQL($query)."%'") : '').'
                '.(($upc == 1) ? ($how_equal == 1 ? " OR pa.upc = '".pSQL($query)."'" : " OR pa.upc LIKE '%".pSQL($query)."%'") : '').'
                '.(($mpn == 1 && version_compare(_PS_VERSION_, '1.7.7.0', '>=')) ? ($how_equal == 1 ? " OR p.mpn = '".pSQL($query)."'" : " OR p.mpn LIKE '%".pSQL($query)."%'") : '').'
                '.(($mpn == 1 && version_compare(_PS_VERSION_, '1.7.7.0', '>=')) ? ($how_equal == 1 ? " OR pa.mpn = '".pSQL($query)."'" : " OR pa.mpn LIKE '%".pSQL($query)."%'") : '').'
                '.(($supplier_reference_all == 1 && version_compare(_PS_VERSION_, '1.5.0.0', '>=')) ? ($how_equal == 1 ? " OR psup.product_supplier_reference = '".pSQL($query)."'" : " OR psup.product_supplier_reference LIKE '%".pSQL($query)."%'") : '').'
                )
                AND ps.id_shop='.pSQL($shop_where).'
            GROUP BY p.id_product
            ORDER BY pl.name ASC,pas.default_on DESC
            LIMIT '.(int) $limit;
    $res = Db::getInstance()->executeS($sql);
}
else
{
    $sql = 'SELECT p.id_product,p.id_category_default,pl.name as pname,cl.name as cname,pl2.name as pname2,pa.id_product_attribute,ps.id_category_default 
            FROM `'._DB_PREFIX_.'product` p
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.id_product=pl.id_product AND pl.id_shop='.pSQL($shop_where).')
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl2 ON (p.id_product=pl2.id_product AND pl2.id_lang='.(int) $sc_agent->id_lang.' AND pl2.id_shop='.pSQL($shop_where).')
            LEFT JOIN `'._DB_PREFIX_.'product_shop` ps ON (ps.id_product = p.id_product AND ps.id_shop='.pSQL($shop_where).')
            LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.id_product=pa.id_product)
            LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` pas ON (pas.id_product_attribute = pa.id_product_attribute AND pas.id_shop='.pSQL($shop_where).')
            LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (cl.id_category=ps.id_category_default AND cl.id_lang='.(int) $sc_agent->id_lang.')
            '.($supplier_reference_all == 1 ? ' LEFT JOIN `'._DB_PREFIX_.'product_supplier` psup ON (psup.id_product=p.id_product) ' : '').'
            WHERE (0
                '.(($reference == 1) ? ($how_equal == 1 ? " OR p.reference = '".pSQL($query)."'" : " OR p.reference LIKE '%".pSQL($query)."%'") : '').'
                '.(($supplier_reference == 1) ? ($how_equal == 1 ? " OR p.supplier_reference = '".pSQL($query)."'" : " OR p.supplier_reference LIKE '%".pSQL($query)."%'") : '').'
                '.(($name == 1) ? ($how_equal == 1 ? " OR pl.name = '".pSQL($query)."'" : " OR pl.name LIKE '%".pSQL($query)."%'") : '').'
                '.(($reference == 1) ? ($how_equal == 1 ? " OR pa.reference = '".pSQL($query)."'" : " OR pa.reference LIKE '%".pSQL($query)."%'") : '').'
                '.(($supplier_reference == 1) ? ($how_equal == 1 ? " OR pa.supplier_reference = '".pSQL($query)."'" : " OR pa.supplier_reference LIKE '%".pSQL($query)."%'") : '').'
                '.(($mpn == 1 && version_compare(_PS_VERSION_, '1.7.7.0', '>=')) ? ($how_equal == 1 ? " OR p.mpn = '".pSQL($query)."'" : " OR p.mpn LIKE '%".pSQL($query)."%'") : '').'
                '.(($mpn == 1 && version_compare(_PS_VERSION_, '1.7.7.0', '>=')) ? ($how_equal == 1 ? " OR pa.mpn = '".pSQL($query)."'" : " OR pa.mpn LIKE '%".pSQL($query)."%'") : '').'
                '.(($supplier_reference_all == 1 && version_compare(_PS_VERSION_, '1.5.0.0', '>=')) ? ($how_equal == 1 ? " OR psup.product_supplier_reference = '".pSQL($query)."'" : " OR psup.product_supplier_reference LIKE '%".pSQL($query)."%'") : '').'
                '.(($short_desc == 1) ? ($how_equal == 1 ? " OR pl.description_short = '".pSQL($query)."'" : " OR pl.description_short LIKE '%".pSQL($query)."%'") : '').'
                '.(($desc == 1) ? ($how_equal == 1 ? " OR pl.description = '".pSQL($query)."'" : " OR pl.description LIKE '%".pSQL($query)."%'") : '').'
                )
                AND ps.id_shop='.pSQL($shop_where).'
            GROUP BY p.id_product
            ORDER BY pl.name ASC,pas.default_on DESC
            LIMIT '.(int) $limit;
    $res = Db::getInstance()->executeS($sql);
}

if ($res != '')
{
    $content = '';
    $plist = array();
    echo '[';
    foreach ($res as $row)
    {
        if (!in_array($row['id_product'], $plist))
        {
            $content .= '{"id_category":"'.$row['id_category_default'].'","id_product":"'.$row['id_product'].'","id_product_attribute":"'.(int) $row['id_product_attribute'].'","pname":"'.str_replace("\'", '', addslashes($row['pname2'])).'","cname":"'.str_replace("\'", '', addslashes($row['cname'])).'"},';
            $plist[] = $row['id_product'];
        }
        if (count($plist) > 25)
        {
            break;
        }
    }
    $content = trim($content, ',');
    echo $content;
    echo ']';
}
