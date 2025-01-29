<?php

namespace InstanWeb\Module\NHBusinessCentral\Filter;

use PrestaShop\PrestaShop\Core\Search\Filters;
use InstanWeb\Module\NHBusinessCentral\Factory\TransactionGridDefinitionFactory;
use InstanWeb\Module\NHBusinessCentral\Model\Transaction;

final class TransactionGridFilter extends Filters
{
    protected $filterId = TransactionGridDefinitionFactory::GRID_ID;

    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'tr.'.Transaction::ID_FIELD,
            'sortOrder' => 'desc',
            'filters' => [],
        ];
    }
}
