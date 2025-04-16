<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'IWSTDImportDB.php';
require_once 'IWSTDImportAcquisitionFile.php';

class IWSTDImportAcquisitionFileFTP extends IWSTDImportAcquisitionFile
{
    protected $connection = null;

    public function acquire()
    {
        try {
            $this->openFtpConnection();
            parent::acquire();
        } finally {
            $this->closeFtpConnection();
        }
        return true;
    }

    protected function openFtpConnection()
    {
        $uri = $this->source_path;
        // Split FTP URI into:
        $match = array(
            parse_url($uri, PHP_URL_SCHEME),
            parse_url($uri, PHP_URL_USER),
            parse_url($uri, PHP_URL_PASS),
            parse_url($uri, PHP_URL_HOST),
            parse_url($uri, PHP_URL_PORT),
            parse_url($uri, PHP_URL_PATH)
        );
        // Set up a connection
        $conn = ftp_connect($match[3], $match[4]);
        // Login
        if (ftp_login($conn, $match[1], $match[2])) {
            // Change the dir
            if (!ftp_chdir($conn, $match[5])) {
                ftp_close($conn);
                throw new Exception(sprintf('Impossible d\'accéder à %1$s de %2$s', $match[5], $match[3]), -1);
            }
            // Return the resource
            $this->connection = $conn;
        }
        if (!isset($this->connection)) {
            throw new Exception(sprintf('Impossible de se connecter à %1$s', $match[3]), -1);
        }
        return true;
    }

    protected function closeFtpConnection()
    {
        if (isset($this->connection)) {
            ftp_close($this->connection);
        }
        $this->connection = null;
        return true;
    }

    protected function scanFiles()
    {
        $wrk = ftp_nlist($this->connection, '.');
        if ($wrk == false) {
            throw new Exception('Impossible de lire le répertoire à partir du serveur FTP', -1);
        }
        $this->items = array();
        foreach ($wrk as $f) {
            $this->addItem($f);
        }
        return true;
    }

    protected function getFiles()
    {
        foreach ($this->items as $f) {
            $destination = IWSTDImportTools::getBackupDataDirectory() . $f;
            if (!ftp_get($this->connection, $destination, $f, FTP_BINARY)) {
                throw new Exception(
                    sprintf('Impossible de prendre %1$s du serveur et de le transférer sur le répertoire de backup', $f),
                    -1
                );
            }
        }
        if ($this->delete_source) {
            foreach ($this->items as $f) {
                if (!ftp_delete($this->connection, $f)) {
                    throw new Exception(sprintf('Impossible d\'effacer %1$s du serveur', $f), -1);
                }
            }
        }
        return true;
    }
}
