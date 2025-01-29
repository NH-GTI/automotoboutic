<?php

namespace InstanWeb\Module\NHBusinessCentral\Tab;

trait TabConfigurationTrait {

    public function tabConfiguration(): array
    {
        return [
            [
                'className' => 'NHBusinessCentral',
                'tabName' => 'NHBusinessCentral',
                'tabNameFr' => 'NHBusinessCentral',
                'parentClassName' => '',
            ],
            [
                'className' => 'ConfigurationController',
                'tabName' => 'Configuration',
                'tabNameFr' => 'Configuration',
                'parentClassName' => 'NHBusinessCentral',
                'icon' => 'settings',
            ],
            [
                'className' => 'TransactionGridController',
                'tabName' => 'Transactions',
                'tabNameFr' => 'Transactions',
                'parentClassName' => 'NHBusinessCentral',
                'icon' => 'refresh',
            ],
        ];
    }
}
