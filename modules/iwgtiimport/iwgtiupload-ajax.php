<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once dirname(__FILE__).'/../../config/config.inc.php';

if ($action = Tools::getValue('action')) {
    if ($action == 'uploadfile') {
        $tmpfile = $_FILES['file']['tmp_name'];
        $file = Tools::getValue('file');
        $filename = Tools::getValue('filename');
        $filename = str_replace('-', '', $filename);  //remove this character because it's used on destination path
        if (!empty($tmpfile)) {
          $fileonly = $filename;
          $typefile = Tools::getValue('typefile');
          if ($typefile == 'importfile') {
            $type = Tools::getValue('type');
            $target = Tools::getValue('target');
            $dst = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'iwgtiimport'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'xlsxsource';
            $filename = $dst.DIRECTORY_SEPARATOR.$type.'-'.($target ? $target.'-' : '').$filename;
          } elseif($typefile == 'imagefile') {
            $subpath = Tools::getValue('subpath');
            $dst = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'iwgtiimport'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$subpath;
            if (file_exists($dst) == false) {
              mkdir($dst);
            }
            $filename = $dst.DIRECTORY_SEPARATOR.$filename;
            if (file_exists($filename)) {
              unlink($filename);
            }
          }
          $fileonly = basename($filename);
  
          if (!move_uploaded_file($tmpfile, $filename)) {
              $msg = 'Le fichier '.$fileonly.' n\'a pas été pris en compte';
          } else {
              $msg = 'Le nouveau fichier '.$fileonly.' a été pris en compte ';
          }
        }
        die(
          Tools::jsonEncode(
            array(
              'msg' => $msg,
              'typefile' => $typefile
            )
          )
        );
    } elseif ($action == 'toggleFileSelection') {
      $id_file = (int)Tools::getValue('id_file');
      $import_files = unserialize(Configuration::get('iwgtiimport_import_files'));
      foreach($import_files as &$ai) {        
        if ($ai['id_file'] == $id_file) {
          $ai['selected'] = $ai['selected'] ? false : true;
        } else {
          $ai['selected'] = false;
        }
      }
      Configuration::updateValue('iwgtiimport_import_files', serialize($import_files));
      die(
        Tools::jsonEncode(
          array(
            'msg' => false
          )
        )
      );
    }
}

