<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$json = [];

switch (Tools::getValue('action')) {
    case 'getProductUrl':
        $active = (bool) Tools::getValue('product_active');
        $id_lang = (int) Tools::getValue('id_lang');
        $product_list = Tools::getValue('productList');
        if (!$product_list)
        {
            break;
        }

        $product_list = json_decode($product_list, true);

        $sc_agent = SC_Agent::getInstance();
        $previewUrlEtraParams = [
            'adtoken' => $sc_agent->getPSToken('AdminCatalog'),
            'id_employee' => $sc_agent->id_employee,
        ];

        $urlList = [];
        foreach ($product_list as $product)
        {
            if ($product['active'])
            {
                $urlList[] = (new Link())->getProductLink(
                    (int) $product['id_product'],
                    null,
                    null,
                    null,
                    $id_lang,
                    (int) SCI::getSelectedShop()
                );
            }
            elseif (version_compare(_PS_VERSION_, '1.7.0.0', '>='))
            {
                $previewUrlEtraParams['preview'] = 1;
                $urlList[] = (new Link())->getProductLink(
                    (int) $product['id_product'],
                    null,
                    null,
                    null,
                    $id_lang,
                    (int) SCI::getSelectedShop(),
                    null,
                    false,
                    false,
                    false,
                    $previewUrlEtraParams
                );
            }
            else
            {
                $url = (new Link())->getProductLink(
                    (int) $product['id_product'],
                    null,
                    null,
                    null,
                    $id_lang,
                    (int) SCI::getSelectedShop()
                );
                $url .= '?'.http_build_query($previewUrlEtraParams);
                $urlList[] = $url;
            }
        }
        $json = $urlList;
        break;
    default:
}

echo json_encode($json);
exit;
