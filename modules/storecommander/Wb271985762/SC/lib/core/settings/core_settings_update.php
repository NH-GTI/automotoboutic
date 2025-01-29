<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$settingId = Tools::getValue('id');
if (!array_key_exists($settingId, $local_settings))
{
    exitJsonResponse([
        'type' => 'error',
        'title' => _l('Invalid setting identifier'),
    ]);
}

$value = Tools::getValue('value');

if (isset($default_settings[$settingId]['formconfig']['min']) && (int) $value < (int) $default_settings[$settingId]['formconfig']['min'])
{
    exitJsonResponse([
        'type' => 'error',
        'title' => _l('%s in incorrect value. Minimal value is : %s', null, [(int) $value, (int) $default_settings[$settingId]['formconfig']['min']]),
    ]);
}

if (isset($default_settings[$settingId]['formconfig']['max']) && (int) $value > (int) $default_settings[$settingId]['formconfig']['max'])
{
    exitJsonResponse([
        'type' => 'error',
        'title' => _l('%s in incorrect value. Maximal value is : %s', null, [(int) $value, (int) $default_settings[$settingId]['formconfig']['max']]),
    ]);
}

if (array_key_exists('formconfig', $default_settings[$settingId]) && $default_settings[$settingId]['formconfig']['type'] === 'color')
{
    $valuesRGB = (array) sscanf($value, '#%02x%02x%02x');
    $value = implode(',', $valuesRGB);
}

if (array_key_exists('saveIntoUiSettings', $default_settings[$settingId]) && (bool) $default_settings[$settingId]['saveIntoUiSettings'])
{
    $employee_settings = UISettings::load_ini_file();
    $employee_settings[$settingId] = $value;
    UISettings::write_ini_file($employee_settings, false);
}
else
{
    if ($settingId == 'CAT_PROD_IMG_UPLOAD_MAX_FILESIZE')
    {
        SCI::updateConfigurationValue('PS_LIMIT_UPLOAD_IMAGE_VALUE', $value);
    }
    $local_settings[$settingId]['value'] = $value;
    saveSettings();
}

$response['type'] = 'success';
if (array_key_exists('needRefresh', $default_settings[$settingId]) && (bool) $default_settings[$settingId]['needRefresh'])
{
    $response['title'] = _l('Setting updated. You need to refresh the page.');
}
else
{
    $response['title'] = _l('Setting updated');
}

exitJsonResponse($response);
