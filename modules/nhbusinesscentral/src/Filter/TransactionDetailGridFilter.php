<?php

namespace InstanWeb\Module\NHBusinessCentral\Filter;

use PrestaShop\PrestaShop\Core\Search\Filters;
use InstanWeb\Module\NHBusinessCentral\Factory\TransactionDetailGridDefinitionFactory;
use InstanWeb\Module\NHBusinessCentral\Model\TransactionDetail;

final class TransactionDetailGridFilter extends Filters
{
    protected $filterId = TransactionDetailGridDefinitionFactory::GRID_ID;

    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'tr.'.TransactionDetail::ID_FIELD,
            'sortOrder' => 'desc',
            'filters' => []
        ];
    }
}
