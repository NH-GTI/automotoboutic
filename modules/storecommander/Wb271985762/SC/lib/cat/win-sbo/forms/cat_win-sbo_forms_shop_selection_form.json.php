<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}
use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();
$sboAccountFound = $shippingboService->getAllSboAccounts();

$shops = SCI::getAllShops(true);
$formShop = [];
$form = [
    'settings' => [
        'type' => 'settings',
        'position' => 'label-right',
    ],
    'title' => [
        'type' => 'label',
        'label' => _l('Which shops would you like to associate with this Shippingbo access?'),
        'className' => '',
    ],
    'error_area' => [
        'type' => 'label',
        'name' => 'error_area',
        'hidden' => true,
        'label' => '',
        'className' => 'message error',
    ],
    'label_notice' => [
        'type' => 'label',
        'label' => _l('You can also change your choice and configure all the shops later').'.',
        'className' => 'message notice',
    ],
];

if($sboAccountFound) {
    $shopsAssociated = $shippingboService->getSboAccount()->getShopIds();
    foreach ($shops as $shop) {
        $form[$shop['id_shop']] = [
            'type' => 'checkbox',
            'name' => "shop_selection[$shop[id_shop]]",
            'label' => $shop['name'],
            'value' => 1,
        ];
        if(in_array((int)$shop['id_shop'], $shopsAssociated)) {
            $form[$shop['id_shop']]['checked'] = true;
        }
    }
}

$form['save'] = [
    'type' => 'button',
    'name' => 'save_selection_shop',
    'className' => 'save_btn',
    'value' => _l('Save'),
];


if(!$sboAccountFound) {
    unset($form['title'], $form['label_notice'], $form['save']);
    $form['error_area']['label'] = _l('No account found. %sPlease go back and create one%s.',null, ['<a href="javascript:wSboTabClick(\'initial-setup\');">', '</a>']);
    $form['error_area']['hidden'] = false;
}

$shippingboService->sendResponse(json_encode(array_values($form)));

?>



