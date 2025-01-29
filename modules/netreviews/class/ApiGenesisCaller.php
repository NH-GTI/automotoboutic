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

/**
 * This class represents the local configuration for the NetReviews module in PrestaShop.
 * It extends the ConfSkeepersProd class and provides specific environment values.
 */
class ApiGenesisCaller
{
    public const GENESISAPIAUTH = '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08';

    /**
     * Error array with status code and message.
     *
     * @var array<array<int, string>>
     */
    private $errors;

    /**
     * @var string
     */
    private $genesisApiUrl;

    public function __construct()
    {
        $this->errors = [];
        $this->genesisApiUrl = '';
        $genesisApiUrl = ConfSkeepers::getInstance()->getEnv('SKEEPERS_API_GENESIS');
        if (!is_string($genesisApiUrl)) {
            $this->errors = [[-3 => 'Error on ApiGenesisCaller::__construct() : Not SKEEPERS_API_GENESIS']];
        } else {
            $this->genesisApiUrl = $genesisApiUrl;
        }
    }

    /**
     * @param array<mixed> $migrationConfiguration
     *
     * @return array<string,mixed>|null
     */
    public function callMigrationEndpoint($migrationConfiguration)
    {
        $url = $this->genesisApiUrl . 'connectors/migrate';

        $headers = [
            'Content-Type: application/json',
            'x-technical-service: connectors',
            'Authorization: ' . self::GENESISAPIAUTH,
        ];

        try {
            $ccurl = (new CurlCapsule($url, [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($migrationConfiguration),
            ], $headers))->addDefaultOpt();

            $ccurl->sendRequest();

            $response = $ccurl->handleSettingsResponse('ApiGenesisCaller::callMigrationEndpoint()');

            $this->errors = $ccurl->getErrors();

            return $response;
        } catch (Exception $e) {
            $this->errors = [[-3 => 'Error on ApiGenesisCaller::callMigrationEndpoint() : ' . $e->getMessage()]];
        } catch (Throwable $e) {
            $this->errors = [[-3 => 'Error on ApiGenesisCaller::callMigrationEndpoint() : ' . $e->getMessage()]];
        }

        return null;
    }

    /**
     * Get the errors that occurred during the last operation.
     *
     * @return array<array<int, string>>
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
