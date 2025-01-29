<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$colSettings['id'] = ['text' => _l('ID'), 'width' => 40, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter'];

$colSettings['active'] = ['text' => _l('Active'), 'width' => 45, 'align' => 'center', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#select_filter', 'options' => [0 => _l('No'), 1 => _l('Yes')], 'onlyforgrids' => ['grid_proppackproduct']];
$colSettings['id_image'] = ['text' => _l('Image'), 'width' => 60, 'align' => 'center', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter', 'onlyforgrids' => ['grid_proppackproduct']];
$colSettings['reference'] = ['text' => _l('Reference'), 'width' => 80, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter'];
$colSettings['name'] = ['text' => _l('Name'), 'width' => 250, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter'];

$colSettings['quantity'] = ['text' => _l('Quantity in pack'), 'width' => 80, 'align' => 'left', 'type' => 'edn', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter', 'footer' => '#stat_total'];
$colSettings['stock_available'] = ['text' => _l('Stock available'), 'width' => 80, 'align' => 'left', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter'];

$colSettings['ean13'] = ['text' => _l('EAN13'), 'width' => 100, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter'];
$colSettings['upc'] = ['text' => _l('UPC'), 'width' => 100, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter'];
$colSettings['mpn'] = ['text' => _l('MPN'), 'width' => 100, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter'];
$colSettings['isbn'] = ['text' => _l('ISBN'), 'width' => 100, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter'];

$colSettings['present'] = ['text' => _l('Present'), 'width' => 100, 'align' => 'center', 'type' => 'ch', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter', 'onlyforgrids' => ['grid_proppackcombi']];
