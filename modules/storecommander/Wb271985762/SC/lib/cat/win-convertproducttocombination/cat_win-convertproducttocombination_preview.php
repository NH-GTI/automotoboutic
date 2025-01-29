<?php

use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductType;

if (!defined('STORE_COMMANDER')) {
    exit;
}


$pdo = Db::getInstance()->getLink();
$productIdsToTransform = array_keys(json_decode(Tools::getValue('product_ids', ''),true));
$productsQuery = new DbQuery();
foreach($productIdsToTransform as $productIdToTransform){
    $productsToTransform[$productIdToTransform] = new Product($productIdToTransform);
}

// forbidden product types
$forbiddenProducts = json_decode(Tools::getValue('forbiddenProducts', ''),true);

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
<div class="service html_content">


    <?php if (!empty($forbiddenProducts)) { ?>
        <div class="message warning">
            <?php echo _l("These products won't be converted because the type associated is not supported for this operation");?> :
            <ul>
                <?php foreach ($forbiddenProducts as $forbiddenProduct) { ?>
                    <li><?php echo '#'.$forbiddenProduct['id'].' '.$forbiddenProduct['name']; ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <div id="products_to_convert">
        <h2><?php echo ucfirst(_l('combinations to create')); ?></h2>

        <table id="combinations_list" style="min-height:80px;" cellpadding="8px" class="no-rowselected-color">
            <thead>
            <tr>
                <th id="header_product_name" width="250"><?php echo _l('Product name'); ?></th>
                <th id="header_product_ref" width="100"><?php echo ucfirst(_l('combination reference')); ?></th>
                <th id="header_is_default" width="100" type="ra"><?php echo ucfirst(_l('default combination')); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($productsToTransform as $productToTransform) { ?>
                <tr id="<?php echo $productToTransform->id; ?>">
                    <td type="ro">#<?php echo $productToTransform->id.' '.$productToTransform->name[$sc_agent->getIdLang()]; ?></td>
                    <td type="ro"><?php echo $productToTransform->reference; ?></td>
                    <td><?php echo (int) $i === 0; ?></td>
                </tr>
            <?php
                $i++;
            } ?>
            </tbody>
        </table>
    </div>

    <div id="convert_products_to_combination_form_container" style="width:100%;margin-top:25px;"></div>

</div>

<ul class="actions">
    <li class="save_btn">
        <button class="btn primary" id="create_combinations">
            <i class="far fa-play"></i>
            <?php echo ucfirst(_l('Create product with combinations')); ?>
        </button>
    </li>
</ul>

</body>
</html>