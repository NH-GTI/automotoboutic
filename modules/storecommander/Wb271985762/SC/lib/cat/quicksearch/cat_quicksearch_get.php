<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

if (!Tools::isSubmit('q'))
{
    exit;
}

$id_product = (bool) Tools::getValue('id_product', true);
$id_product_attribute = (bool) Tools::getValue('id_product_attribute', true);
$name = (bool) Tools::getValue('name', true);
$reference = (bool) Tools::getValue('reference', true);
$supplier_reference = (bool) Tools::getValue('supplier_reference', true);
$supplier_reference_all = (bool) Tools::getValue('supplier_reference_all', true);
$ean = (bool) Tools::getValue('ean', true);
$upc = (bool) Tools::getValue('upc', true);
$mpn = (bool) Tools::getValue('mpn', true);
$short_desc = (bool) Tools::getValue('short_desc');
$desc = (bool) Tools::getValue('desc');
$how_equal = (bool) Tools::getValue('how_equal');

$query = Tools::getValue('q');
$limit = 30;

$sql = new DbQuery();
$sql->select('p.`id_product`, p.`id_category_default`')
    ->select('cl.`name` as cname')
    ->select('pl.`name` as pname')
    ->select('pl2.`name` as pname2')
    ->select('pa.`id_product_attribute`')
    ->select('ps.`id_category_default`')
    ->from('product', 'p')
    ->leftJoin('product_shop', 'ps', 'p.`id_product` = ps.`id_product` AND ps.`id_shop` = '.(int) SCI::getSelectedShop())
    ->leftJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product` AND ps.`id_shop` = ps.`id_shop` AND pl.`id_lang` = '.(int) SC_Agent::getInstance()->getIdLang())
    ->leftJoin('product_lang', 'pl2', 'p.`id_product` = pl2.`id_product` AND pl2.`id_shop` = p.`id_shop_default` AND pl2.`id_lang` = '.(int) SC_Agent::getInstance()->getIdLang())
    ->leftJoin('product_attribute', 'pa', 'p.`id_product` = pa.`id_product`')
    ->leftJoin('product_attribute_shop', 'pas', 'pa.`id_product_attribute` = pas.`id_product_attribute` AND pas.`id_shop` = ps.`id_shop`')
    ->leftJoin('category_lang', 'cl', 'cl.`id_category` = ps.`id_category_default` AND cl.`id_lang` = pl.`id_lang`')
    ->groupBy('p.`id_product`')
    ->orderBy('pl.`name` ASC')
    ->orderBy('pas.`default_on` DESC')
    ->limit($limit)
    ;

## building where with OR condition
$where = [];
if ($id_product && is_numeric($query))
{
    $where[] = 'p.`id_product` = '.(int) $query;
}
if ($id_product_attribute && is_numeric($query))
{
    $where[] = 'pa.`id_product_attribute` = '.(int) $query;
}

if ($name)
{
    $where[] = 'pl2.`name` LIKE "%{query}%"';
}
if ($desc)
{
    $where[] = 'pl.`description` LIKE "%{query}%"';
}
if ($short_desc)
{
    $where[] = 'pl.`description_short` LIKE "%{query}%"';
}

if ($ean)
{
    $where[] = 'p.`ean13` LIKE "%{query}%"';
    $where[] = 'pa.`ean13` LIKE "%{query}%"';
}

if ($reference)
{
    $where[] = 'p.`reference` LIKE "%{query}%"';
    $where[] = 'pa.`reference` LIKE "%{query}%"';
}

if ($supplier_reference)
{
    $where[] = 'p.`supplier_reference` LIKE "%{query}%"';
    $where[] = 'pa.`supplier_reference` LIKE "%{query}%"';
}

if ($upc)
{
    $where[] = 'p.`upc` LIKE "%{query}%"';
    $where[] = 'pa.`upc` LIKE "%{query}%"';
}

if ($mpn && version_compare(_PS_VERSION_, '1.7.7.0', '>='))
{
    $where[] = 'p.`mpn` LIKE "%{query}%"';
    $where[] = 'pa.`mpn` LIKE "%{query}%"';
}

if ($supplier_reference_all)
{
    $sql->leftJoin('product_supplier', 'psup', 'psup.`id_product` = p.`id_product`');
    $where[] = 'psup.`product_supplier_reference` LIKE "%{query}%"';
}

$where = implode(' OR ', $where);

## stric equal we need =
if ($how_equal)
{
    $where = str_replace('LIKE "%{query}%"', '= "{query}"', $where);
}

$where = str_replace('{query}', pSQL($query), $where);

## finalize sql request
$sql->where($where)
    ->where('ps.`id_shop` = '.(int) SCI::getSelectedShop())
    ;
try
{
    $results = Db::getInstance()->executeS($sql);
    if (!$results)
    {
        exit(json_encode([
            [
                'id_category' => 0,
                'id_product' => 0,
                'id_product_attribute' => 0,
                'cat_parent_list' => 0,
                'pname' => _l('No result'),
                'cname' => '',
                'found' => 0,
            ],
        ]));
    }
}
catch (Exception $e)
{
    exit;
}

$content = [];
$plist = [];

foreach ($results as $row)
{
    if (!in_array($row['id_product'], $plist))
    {
        $idDefaultCategory = (int) $row['id_category_default'];

        $searchCategory = new Category($idDefaultCategory);
        $catSelectionList = [];
        $sql = new DbQuery();
        $sql->select('`id_category`')
            ->from('category')
            ->where('`nleft` < '.(int) $searchCategory->nleft)
            ->where('`nright` > '.(int) $searchCategory->nright)
            ->where('`id_parent` > 0')
            ->where('`is_root_category` = 0')
            ->orderBy('`nleft` ASC')
            ;
        $categoryList = Db::getInstance()->executeS($sql);
        if ($categoryList)
        {
            $catSelectionList = array_column($categoryList, 'id_category');
            $catSelectionList[] = $idDefaultCategory;
        }

        $content[] = [
            'id_category' => (int) $row['id_category_default'],
            'id_product' => (int) $row['id_product'],
            'id_product_attribute' => (int) $row['id_product_attribute'],
            'cat_parent_list' => $catSelectionList,
            'pname' => $row['pname2'],
            'cname' => $row['cname'],
            'found' => 1,
        ];
        $plist[] = $row['id_product'];
    }
    if (count($plist) >= $limit)
    {
        break;
    }
}
exit(json_encode($content));
