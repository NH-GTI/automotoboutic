<?php

namespace InstanWeb\Module\NHBusinessCentral\Provider;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

class ConfigurationData implements FormDataProviderInterface
{
    private $configuration;
    
    public function __construct(DataConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getData(): array
    {
        return $this->configuration->getConfiguration();
    }

    public function setData(array $data): array
    {
        return $this->configuration->updateConfiguration($data);
    }

    public function getPrefix()
    {
        return $this->configuration->getPrefix();
    }
}

