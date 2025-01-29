<?php


/** This file is part of KCFinder project
  *
  *      @desc Browser calling script
  *   @package KCFinder
  *   @version 3.12
  *    @author Pavel Tzonkov <sunhater@sunhater.com>
  * @copyright 2010-2014 KCFinder Project
  *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
  *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
  *      @link http://kcfinder.sunhater.com
  */

/* SC */
define('SC_DIR',dirname(__file__).'/../../../../');
define('_PS_ADMIN_DIR_', 1); // for PS1.5
if (strpos(SC_DIR,'modules')===false) // installation in /adminXXX/
{
    define('SC_INSTALL_MODE',0);
    define('SC_PS_PATH_DIR',realpath(SC_DIR.'../../').'/');
    define('SC_PS_PATH_REL','../../');
}else{ // installation in /modules/
    define('SC_INSTALL_MODE',1);
    define('SC_PS_PATH_DIR',realpath(SC_DIR.'../../../../').'/');
    define('SC_PS_PATH_REL','../../../../');
}
require_once(SC_PS_PATH_DIR.'config/config.inc.php');

## SC can be called ?
$current_sc_module_folder_name = basename(realpath(SC_DIR.'../../'));
if (version_compare(_PS_VERSION_, '1.7', '>='))
{
    $legacyLogger = new PrestaShop\PrestaShop\Adapter\LegacyLogger();
    $moduleDataProvider = new PrestaShop\PrestaShop\Adapter\Module\ModuleDataProvider($legacyLogger, Context::getContext()->getTranslator());
    $scInstalled = $moduleDataProvider->isInstalled($current_sc_module_folder_name);
}
else
{
    $scInstalled = Module::isInstalled($current_sc_module_folder_name);
}
if(!$scInstalled || !Module::isEnabled($current_sc_module_folder_name))
{
    header('HTTP/1.1 403 Forbidden');
    exit;
}

require_once(SC_DIR.'lib/php/agent.php');

$sc_agent = new SC_Agent();

// Test if employee is connected
$ajax = Tools::getValue('ajax', 0);
if (!$sc_agent->isLoggedBack()) {
    die('You must be logged to use CKEDITOR.');
}
/* END SC */

require "core/bootstrap.php";
$browser = "kcfinder\\browser"; // To execute core/bootstrap.php on older
$browser = new $browser();      // PHP versions (even PHP 4)
$browser->action();