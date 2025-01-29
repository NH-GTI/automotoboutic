<?php

namespace InstanWeb\Module\NHBusinessCentral\Adapter\Config;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

final class BusinessCentralConfiguration implements DataConfigurationInterface
{
    use ConfigurationTrait;

    public const PREFIX = 'NHBUSINESSCENTRAL_BC_';

    public const MAIL_NOTIFICATION = 'NHBUSINESSCENTRAL_BC_MAIL_NOTIFICATION';

    public const TENANT_ID         = 'NHBUSINESSCENTRAL_BC_TENANT_ID';
    public const CLIENT_ID         = 'NHBUSINESSCENTRAL_BC_CLIENT_ID';
    public const COMPANY_ID        = 'NHBUSINESSCENTRAL_BC_COMPANY_ID';
    public const CLIENT_SECRET     = 'NHBUSINESSCENTRAL_BC_CLIENT_SECRET';
    public const BASE_URI          = 'NHBUSINESSCENTRAL_BC_BASE_URI';
    public const SALES_WS          = 'NHBUSINESSCENTRAL_BC_SALES_WS';
    public const SCOPE             = 'NHBUSINESSCENTRAL_BC_SCOPE';
    public const ENVIRONMENT       = 'NHBUSINESSCENTRAL_BC_ENVIRONMENT';
    public const VERSION           = 'NHBUSINESSCENTRAL_BC_VERSION';
    public const TOKEN_URL         = 'NHBUSINESSCENTRAL_BC_TOKEN_URL';

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
        $this->consts = [
            static::MAIL_NOTIFICATION,
            static::TENANT_ID,
            static::CLIENT_ID,
            static::COMPANY_ID,
            static::CLIENT_SECRET,
            static::BASE_URI,
            static::SALES_WS,
            static::SCOPE,
            static::ENVIRONMENT,
            static::VERSION,
            static::TOKEN_URL,
        ];
    }
}
