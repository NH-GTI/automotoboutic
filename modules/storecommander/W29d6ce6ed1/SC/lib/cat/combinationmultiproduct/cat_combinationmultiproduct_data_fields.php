<?php
if (!defined('STORE_COMMANDER')) { exit; }

$colSettings['id_product'] = array('text' => _l('id prod.'), 'width' => 40, 'align' => 'left', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');
$colSettings['id_product_attribute'] = array('text' => _l('id prod. attr.'), 'width' => 40, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');
$colSettings['combination_name'] = array('text' => _l('Combination name'), 'width' => 200, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['product_name'] = array('text' => _l('Product name'), 'width' => 150, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['reference'] = array('text' => _l('Ref'), 'width' => 80, 'align' => 'left', 'type' => 'edtxt', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['supplier_reference'] = array('text' => _l('Supplier Ref.'), 'width' => 90, 'align' => 'left', 'type' => 'edtxt', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['quantity'] = array('text' => _l('Stock available'), 'width' => 60, 'align' => 'right', 'type' => 'ed', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');

$colSettings['minimal_quantity'] = array('text' => _l('Minimum quantity'), 'width' => 60, 'align' => 'right', 'type' => 'ed', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');
$colSettings['ecotax'] = array('text' => _l('EcoTax'), 'width' => 50, 'align' => 'right', 'type' => 'edn', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter', 'format' => '0.00');
$colSettings['wholesale_price'] = array('text' => _l('Wholesale price'), 'width' => 55, 'align' => 'right', 'type' => 'ed', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');
$colSettings['available_date'] = array('text' => _l('Available date'), 'width' => 80, 'align' => 'right', 'type' => 'dhxCalendarA', 'sort' => 'date', 'color' => '', 'filter' => '#select_filter', 'format' => '%Y-%m-%d');
$colSettings['weight'] = array('text' => _l('Att. weight'), 'width' => 65, 'align' => 'right', 'type' => 'ed', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');

$colSettings['price'] = array('text' => _l('Attr. price'), 'width' => 50, 'align' => 'right', 'type' => 'edn', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter', 'format' => '0.00');
$colSettings['pprice'] = array('text' => _l('Prod. price'), 'width' => 50, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter', 'format' => '0.00');
$colSettings['ppriceextax'] = array('text' => _l('Prod. price excl tax'), 'width' => 50, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter', 'format' => '0.00');
$colSettings['priceextax'] = array('text' => _l('Attr. price excl tax'), 'width' => 50, 'align' => 'right', 'type' => 'edn', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter', 'format' => '0.00');
$colSettings['margin'] = array('text' => _l('Margin'), 'width' => 45, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter', 'format' => '0.00');
$colSettings['taxrate'] = array('text' => _l('Tax rate'), 'width' => 50, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');

$colSettings['sc_active'] = array('text' => _l('Used'), 'width' => 50, 'align' => 'center', 'type' => 'co', 'sort' => 'str', 'color' => '', 'filter' => '#select_filter', 'options' => array(0 => _l('No'), 1 => _l('Yes')));

// GE
if (version_compare(_PS_VERSION_, '1.7.5.0', '>='))
{
    $colSettings['soft_qty_physical'] = array('text' => _l('Physical quantity'), 'width' => 60, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');
    $colSettings['soft_qty_reserved'] = array('text' => _l('Reserved quantity'), 'width' => 60, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');
}
if (version_compare(_PS_VERSION_, '1.7.7.0', '>='))
{
    $colSettings['mpn'] = array('text' => _l('MPN'), 'width' => 100, 'align' => 'left', 'type' => 'edtxt', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
}

$colSettings['ean13'] = array('text' => _l('EAN13'), 'width' => 100, 'align' => 'left', 'type' => 'edtxt', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['upc'] = array('text' => _l('UPC'), 'width' => 100, 'align' => 'left', 'type' => 'edtxt', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['location'] = array('text' => _l('Location').(SCAS ? ' '._l('(old)') : ''), 'width' => 100, 'align' => 'left', 'type' => 'edtxt', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');

$colSettings['pweight'] = array('text' => _l('Prod. weight'), 'width' => 50, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#text_filter');
$colSettings['unit_price_impact'] = array('text' => _l('unit_price_impact'), 'width' => 50, 'align' => 'right', 'type' => 'ed', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');
