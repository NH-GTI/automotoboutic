<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$action = Tools::getValue('action');
$scUniqueId = SCI::getConfigurationValue('SC_UNIQUE_ID');

switch ($action)
{
    case 'get_automatic_update':
        $autoUpdateInfo = ['code' => 400, 'result' => ''];
        $response = [
            'type' => 'error',
        ];

        if (!SC_DEMO)
        {
            $autoUpdateInfo = makeDefaultCallToOurApi('externhall/autoupdate/get', ['unique-id' => $scUniqueId]);
        }
        if ((int) $autoUpdateInfo['code'] === 200 && !empty($autoUpdateInfo['result']))
        {
            $response = [
                'type' => 'success',
            ];
        }
        break;
    case 'set_automatic_update':
        $scUniqueId = SCI::getConfigurationValue('SC_UNIQUE_ID');
        $autoUpdateInfo = ['code' => 400, 'result' => ''];

        if (!SC_DEMO)
        {
            $autoUpdateInfo = makeDefaultCallToOurApi('externhall/autoupdate/set', ['unique-id' => $scUniqueId]);
        }

        $setAutoUpdate = (bool) Tools::getValue('value');
        $apiResult = makeDefaultCallToOurApi('externhall/autoupdate/set', ['unique-id' => $scUniqueId], ['autoupdate' => $action]);

        $enableTitle = [
            'success' => 'Autoupdate successfully enabled',
            'error' => 'Error while activating the automatic update',
        ];
        $disableTitle = [
            'success' => 'Autoupdate successfully disabled',
            'error' => 'Error while disabling the automatic update',
        ];

        $response = [
            'type' => 'error',
            'title' => _l(($setAutoUpdate ? $enableTitle['error'] : $disableTitle['error'])),
            'message' => (!empty($apiResult['result']) ? $apiResult['result'] : ''),
        ];

        if ((int) $apiResult['code'] == 200)
        {
            $response = [
                'type' => 'success',
                'title' => _l(($setAutoUpdate ? $enableTitle['success'] : $disableTitle['success'])),
                'message' => (!empty($apiResult['result']) ? $apiResult['result'] : ''),
            ];
        }
        break;
    case 'submitScUpdate':
        if (empty(SCI::getConfigurationValue('SC_LICENSE_KEY', '')))
        {
            $response = [
                'type' => 'error',
                'title' => _l('An error occurend'),
                'message' => _l('You have to register your license key in the [Help > Register your license] menu to update Store Commander.'),
            ];
            break;
        }

        try
        {
            ob_start();
            doScUpdate($user_lang_iso);
            $moreinfos = ob_get_contents();
            ob_end_clean(); ## do not show echos from doScUpdate()

            $lastVersion = json_decode(SCI::getConfigurationValue('SC_VERSIONS_LAST'), true);
            $response = [
                'type' => 'success',
                'title' => _l('%s is up to date. Installed version: %s', null, ['Store Commander', $lastVersion['SC-Pack1']['version']]),
                'message' => _l('Window will be reloaded in %s seconds', null, [2]).'<br>'._l('Date of last update')._l(':').' '.SCI::getConfigurationValue('SC_LAST_UPDATE'),
                'moreinfo'=> $moreinfos
            ];
        }
        catch (Exception $e)
        {
            $response = [
                'type' => 'error',
                'title' => _l('An error occurend'),
                'message' => $e->getMessage(),
            ];
        }
        break;
    default:
        $response = [
            'type' => 'error',
            'title' => _l('An error occurend'),
            'message' => _l('Invalid action'),
        ];
}

header('Content-type: application/json');
echo json_encode($response);
exit;
