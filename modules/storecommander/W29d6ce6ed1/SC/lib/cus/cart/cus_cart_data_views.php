<?php
if (!defined('STORE_COMMANDER')) { exit; }

$grids = 'id_cart,id_customer,email,shop_name,product_date_add,id_product,id_product_attribute,stock_available,quantity,product_name';

if (!SCMS || count(SCI::getSelectedShopActionList()) === 1){
    $grids = str_replace(',shop_name','',$grids);
}