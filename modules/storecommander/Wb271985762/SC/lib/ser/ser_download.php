<?php
if (!defined('STORE_COMMANDER')) { exit; }

$item = Tools::getValue('item', null);
$response = array(
    'status' => 'error',
    'message' => _l('Empty data'),
);

if ($item)
{
    switch ($item)
    {
        case 'Affiliation':
        case 'CatalogPDF':
            $nameMapping = array(
                    'Affiliation' => '<b>'._l('Affiliation program').'</b>',
                    'CatalogPDF' => '<b>'._l('PDF Catalog').'</b>',
                );
            $downloaded = checkModuleAndDownload($item);
            switch ($downloaded)
            {
                case 0:
                    $response['message'] = _l('Unable to download: %s', false, array($nameMapping[$item]));
                    break;
                case 1:
                    $response['status'] = 'success';
                    $response['message'] = _l('Please refresh the window after installing: %s', false, array($nameMapping[$item]));
                    break;
                case 2:
                    $response['message'] = _l('%s is already installed', false, array($nameMapping[$item]));
                    break;
            }
            break;
    }
}

exit(json_encode($response));
