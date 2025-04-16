<?php
/**
 *  Tous droits réservés NDKDESIGN
 *
 *  @author    Hendrik Masson <postmaster@ndk-design.fr>
 *  @copyright Copyright 2013 - 2014 Hendrik Masson
 *  @license   Tous droits réservés
*/

include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../../../init.php');
function ndkCheckEnvironment()
{
    $cookie = new Cookie('psAdmin', '', (int)Configuration::get('PS_COOKIE_LIFETIME_BO'));
    return isset($cookie->id_employee) && isset($cookie->passwd) && Employee::checkPassword($cookie->id_employee, $cookie->passwd);
}
if(!ndkCheckEnvironment())
die('forbidden');


require_once _PS_MODULE_DIR_.'ndk_advanced_custom_fields/import/ndkCfImporter.php';
$datas = array();

$serviceClass = 'ndkCf'.ucfirst(Tools::getValue('service'));
if(isset($serviceClass))
{
    $serviceFile = _PS_MODULE_DIR_.'ndk_advanced_custom_fields/import/'.$serviceClass.'.php';
    if(file_exists($serviceFile))
    {
        require_once $serviceFile;
        $datas = $serviceClass::runDatas();
    }
}

