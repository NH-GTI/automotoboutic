<?php
if (!defined('STORE_COMMANDER')) { exit; }

$colSettings['id_image'] = array('text' => _l('ID'), 'width' => 40, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');
$colSettings['id_product'] = array('text' => _l('id_product'), 'width' => 40, 'align' => 'right', 'type' => 'ro', 'sort' => 'int', 'color' => '', 'filter' => '#numeric_filter');
$colSettings['image'] = array('text' => _l('Image'), 'width' => 150, 'align' => 'center', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['name'] = array('text' => _l('Name'), 'width' => 200, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['reference'] = array('text' => _l('Ref'), 'width' => 80, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['legend'] = array('text' => _l('Legend'), 'width' => 200, 'align' => 'left', 'type' => 'edtxt', 'sort' => 'str', 'color' => '', 'filter' => '#text_filter');
$colSettings['position'] = array('text' => _l('Pos.'), 'width' => 40, 'align' => 'right', 'type' => 'edtxt', 'sort' => 'int', 'color' => '', 'filter' => '#text_filter');
$colSettings['cover'] = array('text' => _l('Default'), 'width' => 20, 'align' => 'center', 'type' => 'ra', 'sort' => 'int', 'color' => '', 'filter' => '#select_filter');

$colSettings['_SHOPS_'] = array('text' => '_SHOPS_', 'width' => 80, 'align' => 'center', 'type' => 'ch', 'sort' => 'int', 'color' => '', 'filter' => '#select_filter');
$colSettings['width'] = array('text' => _l('Width'), 'width' => 80, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#select_filter');
$colSettings['height'] = array('text' => _l('Height'), 'width' => 80, 'align' => 'left', 'type' => 'ro', 'sort' => 'str', 'color' => '', 'filter' => '#select_filter');
