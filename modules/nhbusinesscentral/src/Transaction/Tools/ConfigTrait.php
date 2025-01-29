<?php

namespace InstanWeb\Module\NHBusinessCentral\Transaction\Tools;

trait ConfigTrait
{
    public function getObjectConfig()
    {
        $values = $this->configuration->getData();
        $object = [];
        foreach($values as $key => $value) {
            $key = str_replace($this->configuration->getPrefix(), '', $key); 
            $object[strtolower($key)] = $value;
        }
        return (object)$object;
    }

}