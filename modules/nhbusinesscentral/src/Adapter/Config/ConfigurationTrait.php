<?php

namespace InstanWeb\Module\NHBusinessCentral\Adapter\Config;

trait ConfigurationTrait {

    private $consts;

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    public function getConfiguration(): array
    {
        $result = [];
        foreach($this->consts as $const) {
            $result[$const] = $this->configuration->get($const);
        }
        return $result;
    }

    public function updateConfiguration(array $configuration): array
    {
        $errors = [];

        if ($this->validateConfiguration($configuration)) {
            foreach($this->consts as $const) {
                $this->configuration->set($const, $configuration[$const]);
            }
        } else {
            $errors[] = 'error';
        }

        /* Errors are returned here. */
        return $errors;
    }

    /**
     * Ensure the parameters passed are valid.
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool
    {
        return true;
    }

    public function getPrefix()
    {
        return static::PREFIX;
    }
}