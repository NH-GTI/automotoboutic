<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

use Sc\Service\Shippingbo\Shippingbo;

$shippingboService = Shippingbo::getInstance();

$SCMS = SCMS;


$formSettings = [
    'type' => 'settings',
    'position' => 'label-right',
    'labelAlign' => 'left',
];

$formMessage = [
    'type' => 'container',
    'name' => 'warning_message',
    'position' => 'label-right',
    'label' => '<p class="message warning">'._l('Actions in this panel may cause data loss, please use it only if you know what you\'re doing', 1).'</p>',
];


$formShopRelated = [
    [
        'type' => 'fieldset',
        'label' => $SCMS?_l('Shops'):_l('Shop'),
        'list' => [
            [
                'type' => 'checkbox',
                'name' => 'safe_remove_shop_relations',
                'label' => _l('Clear relation table').' ('._l('No data loss').')',
                'checked' => false,
            ],
            [
                'type' => 'checkbox',
                'disabled' => true,
                'name' => 'remove_all_shop_relations',
                'offsetLeft' =>  '50',
                'label' => _l('Remove product selection'),
                'checked' => false,
            ],
            [
                'type' => 'checkbox',
                'name' => 'clear_sbo_segment',
                'label' => _l('Remove Shippingbo Segmentation and all containing products').' <br/><em>'._l('Products attached to a category will not be removed from PrestaShop database').'</em>',
                'checked' => false,
            ],
            [
                'type' => 'button',
                'name' => 'save_advanced_db_shop',
                'value' => _l('Validate'),
                'className' => 'save_btn',
            ]
        ],
    ],

];

$formSboAccountRelated = [
    [
        'type' => 'fieldset',
        'label' => $SCMS?_l('Shippingbo accounts'):_l('Shippingbo account'),
        'list' => [
            [
                'type' => 'checkbox',
                'name' => 'clear_sbo_buffer',
                'label' => _l('Clear buffer tables').' ('._l('No data loss').')',
                'checked' => false,
            ],
            [
                'type' => 'checkbox',
                'name' => 'clear_sbo_service',
                'label' => _l('Clear Shippingbo settings'),
                'checked' => false,
            ],
            [
                'type' => 'button',
                'name' => 'save_advanced_db_account',
                'value' => _l('Validate'),
                'className' => 'save_btn',
            ]
        ],
    ],
];

$formConnectorRelated = [
    [
        'type' => 'fieldset',
        'label' => _l('Shippingbo connector'),
        'list' => [
            [
                'type' => 'checkbox',
                'name' => 'reset_connector',
                'label' => _l('Reset the connector').' <div class="message danger">'._l('All the connector information will be removed from the database').'</div>',
                'checked' => false,
            ],
            [
                'type' => 'button',
                'name' => 'save_advanced_db_connector',
                'value' => _l('Validate'),
                'className' => 'save_btn',
            ]
        ],
    ],
];



if($SCMS){

    $options = $shippingboService->getAllAvailableShopsForSelect();
    array_unshift($options,[ 'text' => _l('All shops'), 'value' => 0]);
    $shopSelect = [
        'type' => 'select',
        'name' => 'id_shop',
        'className' => 'important',
        'position' => 'label-left',
        'options' => $options,
    ];
    array_unshift($formShopRelated[0]['list'],$shopSelect);

    $options = $shippingboService->getAllSboAccountsForSelect();
    array_unshift($options,[ 'text' => _l('All Shippingbo accounts'), 'value' => 0]);
    $sboAccountSelect = [
        'type' => 'select',
        'name' => 'id_sbo_account',
        'className' => 'important',
        'position' => 'label-left',
        'options' => $options
    ];

    array_unshift($formSboAccountRelated[0]['list'],$sboAccountSelect);
}

$formContent = array_merge($formShopRelated, $formSboAccountRelated,$formConnectorRelated);



$formAdvanced = [
    $formSettings,
    $formMessage,
        [
            'type' => 'block',
            'className' => 'sbo_expert_form',
            'list' => $formContent
        ],
];
$shippingboService->sendResponse(json_encode($formAdvanced));

