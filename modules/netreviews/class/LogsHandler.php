<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    NetReviews SAS <contact@avis-verifies.com>
 * @copyright 2012-2024 NetReviews SAS
 * @license   NetReviews
 *
 * @version   Release: $Revision: 9.0.0
 *
 * @date      22/08/2024
 * International Registered Trademark & Property of NetReviews SAS
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class LogsHandler
{
    /**
     * @param string $message
     * @param int $severity
     *
     * @return void
     */
    public static function addLog($message, $severity = 2)
    {
        $now = new DateTime();
        $now->setTimezone(new DateTimeZone('Europe/Paris'));
        $dateTime = $now->format('Y-m-d H:i:s');
        $fileName = dirname(__FILE__) . '/../logs.txt';
        try {
            $fp = fopen($fileName, 'a+'); // path: prestashop\modules\netreviews
            if ($fp) {
                $filePerms = self::getFilePerms($fileName);
                if ($filePerms >= 640 && 700 != $filePerms) {
                    @chmod($fileName, 0600);
                    clearstatcache();
                }
                $newFilePerms = self::getFilePerms($fileName);
                $allowedPerms = [600, 700];
                if (in_array($newFilePerms, $allowedPerms)) {
                    fwrite(
                        $fp,
                        $dateTime . ' [Netreviews - ' . $severity . '] ' .
                        Tools::safeOutput($message) . PHP_EOL . PHP_EOL
                    );
                }
            }
            if ($fp && is_resource($fp)) {
                fclose($fp);
            }
        } catch (Exception $e) {
            // no log
            return;
        }
    }

    /**
     * @param string $filename
     *
     * @return int
     */
    private static function getFilePerms($filename)
    {
        return (int) substr(sprintf('%o', fileperms($filename)), -4);
    }
}
