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

class ConfSkeepers
{
    /**
     * Variable to store the env variables
     *
     * @var array<string, int|bool|string|null>
     */
    private $envVars;

    /**
     * @var ConfSkeepers|null
     */
    private static $instance;

    private function __construct()
    {
        $this->envVars = ConfSkeepersProd::getAllEnVar();
        if (class_exists('ConfSkeepersLocal')) {
            $this->envVars = array_merge($this->envVars, ConfSkeepersLocal::getAllEnVar());
        }
    }

    /**
     * @return ConfSkeepers
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new ConfSkeepers();
        }

        return self::$instance;
    }

    /**
     * @param string $key
     *
     * @return bool|int|string|null
     *
     * @throws Exception
     */
    public function getEnv($key)
    {
        if (!array_key_exists($key, $this->envVars)) {
            throw new Exception('No env variable defined');
        }

        return $this->envVars[$key];
    }
}
