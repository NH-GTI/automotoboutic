<?php
if (!defined('STORE_COMMANDER'))
{
    exit;
}

$updateMainForm = [
    [
        'type' => 'label',
        'label' => '<h1>' . _l('Update') . '</h1>'
    ],
    [
        'id' => 'messageInformation',
        'name' => 'messageInformation',
        'type' => 'template',
        'value' => [
            'class' => 'info',
            'title' => _l('You have a new version to install'),
            'content' => _l('Date of last update') . _l(':') . ' ' . SCI::getConfigurationValue('SC_LAST_UPDATE')
        ],
        'format' => 'modalMessage'
    ],
    [
        'id' => 'infoAboutUpdateAvailable',
        'type' => 'label',
        'label' => '<h2>' . _l('We strongly recommend that you enable automatic updates.') . '</h2>
                <p>' . _l('Updates will be performed overnight.') . '<a href="' . getScExternalLink('core_update_autoupdate') . '" target="_blank">' . _l('More informations') . '</a></p>'
    ],
    [
        'inputWidth' => 42,
        'position' => 'label-right',
        'type' => 'btn2state',
        'name' => 'set_automatic_update',
        'label' => _l('Enable automatic updates'),
        'checked' => false
    ],
    [
        'type' => 'template',
        'format' => 'modalSeparator'
    ],
    [
        'type' => 'block',
        'className' => 'columns',
        'list' => [
            [
                'className' => 'checkConditions',
                'position' => 'label-right',
                'type' => 'checkbox',
                'name' => 'termsconditions',
                'checked' => false,
                'label' => '<span>' . _l('I accept') . '</span> <span class="dhxform_info">' . _l('Terms & Conditions') . '</span>'
            ],
            [
                'type' => 'newcolumn'
            ],
            [
                'className' => 'validationSubmit',
                'name' => 'submitScUpdate',
                'disabled' => true,
                'type' => 'button',
                'value' => _l('Update now')
            ]
        ]
    ]
];

$updateTermAndConditionConfigForm = [
    [
        'type' => 'label',
        'label' => '<h1>' . _l('Terms and conditions') . '</h1>'
    ],
    [
        'type' => 'template',
        'format' => 'modalSeparator'
    ],
    [
        'type' => 'container',
        'name' => 'containerTermsAndConditions'
    ],
    [
        'type' => 'template',
        'format' => 'modalSeparator'
    ],
    [
        'type' => 'block',
        'className' => 'columns',
        'list' => [
            [
                'className' => 'validationSubmit',
                'type' => 'button',
                'name' => 'download',
                'value' => '<i class="far fa-file-download"></i> ' . _l('Download pdf')
            ],
            [
                'type' => 'newcolumn'
            ],
            [
                'className' => 'alignRight',
                'type' => 'button',
                'name' => 'close',
                'value' => _l('Close')
            ]
        ]
    ]
];

echo '<script>';
?>
    scWindowCoreUpdate._windowCoreUpdateMainForm = scWindowCoreUpdate.attachForm(<?php echo json_encode($updateMainForm); ?>)

    // set default autoupdate
    $.post('index.php?ajax=1&act=core_update_update', {
        action: 'get_automatic_update'
    },function(response) {
        if(response.type === 'success') {
            scWindowCoreUpdate._windowCoreUpdateMainForm.setItemValue('set_automatic_update', true);
        } else {
            scWindowCoreUpdate._windowCoreUpdateMainForm.setItemValue('set_automatic_update', false);
        }
    })

    scWindowCoreUpdate._windowCoreUpdateMainForm.cont.classList.add('scModalForm')
    scWindowCoreUpdate._windowCoreUpdateMainForm.attachEvent('onChange', function(itemId, value, state){
        switch (itemId){
            case 'set_automatic_update':
                $.post('index.php?ajax=1&act=core_update_update', {
                    action: itemId,
                    value : Number(state)
                }, function(response){
                    setReponseInformation(response)
                })
                break;
            case 'termsconditions':
                if(state) {
                    scWindowCoreUpdate._windowCoreUpdateMainForm.enableItem('submitScUpdate');
                } else {
                    scWindowCoreUpdate._windowCoreUpdateMainForm.disableItem('submitScUpdate');
                }
                break;
        }
    });
    scWindowCoreUpdate._windowCoreUpdateMainForm.attachEvent('onButtonClick', function(itemId){
        let itemValue = scWindowCoreUpdate._windowCoreUpdateMainForm.getItemValue(itemId)
        switch (itemId)
        {
            case 'submitScUpdate':
                setReponseInformation({
                    type: 'process',
                    title: '<?php echo _l('Update in progress', true); ?>',
                    message: '<?php echo _l('Application will restart automatically at the end of the update', true); ?>'
                })

                $.post('index.php?ajax=1&act=core_update_update', {
                    action: itemId,
                    value : Number(itemValue)
                }, function(response){
                    if(typeof response === 'object') {
                        setReponseInformation(response)

                        if(response.type === 'success')
                        {
                            setTimeout(function(){
                               location.reload();
                            }, 2000);
                        }
                    } else {
                        setReponseInformation({
                            type: 'error',
                            title: '<?php echo _l('An error occurend', true); ?>',
                            message: response
                        })
                    }
                })
                break;
        }
    });

    document.querySelector('.scModalForm .checkConditions .dhxform_info').addEventListener('click', function(){
        openTermsAndConditionsWindow();
    })
    function openTermsAndConditionsWindow(){
        if (!Boolean(dhxWins.isWindow('scWindowTermsAndConditions'))) {
            window.scWindowTermsAndConditions = dhxWins.createWindow('scWindowTermsAndConditions', 40, 40, 900, $(window).height()-150)
        }
        scWindowTermsAndConditions.centerOnScreen();
        scWindowTermsAndConditions.cell.offsetParent.id = 'scWindowTermsAndConditions'
        scWindowTermsAndConditions.button('minmax').hide();
        scWindowTermsAndConditions.button('park').hide();

        const scWindowTermsAndConditionsMainForm = scWindowTermsAndConditions.attachForm(<?php echo json_encode($updateTermAndConditionConfigForm); ?>)
        scWindowTermsAndConditionsMainForm.cont.classList.add('scModalForm')
        scWindowTermsAndConditionsMainForm.getContainer('containerTermsAndConditions').insertAdjacentHTML('beforeend', '<iframe id="cgu_detail" src="<?php echo CGU_EXTERNAL_URL; ?>"></iframe>')
        scWindowTermsAndConditionsMainForm.attachEvent('onButtonClick', function(itemId){
             switch (itemId) {
                 case 'download':
                     window.open('<?php echo CGU_EXTERNAL_URL_PDF; ?>')
                     break;
                 case 'close':
                     scWindowTermsAndConditions.close();
                     break;
             }
        });
    }

    function setReponseInformation(response)
    {
        scWindowCoreUpdate._windowCoreUpdateMainForm.setItemValue('messageInformation',{
            class: response.type,
            title: response.title,
            content: response.message
        })
        scWindowCoreUpdate._windowCoreUpdateMainForm.updateValues();
    }
<?php echo '</script>'; ?>
