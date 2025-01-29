<?php

namespace Sc\Service\Shippingbo\Repository;

use DbQuery;
use Sc\Service\Model\ServiceConfigurationModel;
use Sc\Service\Shippingbo\Model\ShippingboAccountModel;

class ShippingboAccountRepository
{
    public function getAllQuery()
    {
        $dbQuery = (new DbQuery())
            ->select('*')
            ->from((new ShippingboAccountModel())->getTableName(), 'sbo_account')
        ;

        return $dbQuery;
    }

    public function getOneByIdQuery()
    {
        $dbQuery = $this->getAllQuery();
        $dbQuery->where('sbo_account.id_account = :id');

        return $dbQuery;
    }

    /**
     * @return \DbQuery
     */
    public function getAllByShopIdQuery()
    {
        return (new \DbQuery())
           ->select('*')
           ->from((new ServiceConfigurationModel())->getTableName(), 'service_configuration')
           ->where('service_configuration.id_shop = :id_shop')
           ->where('service_configuration.name = :name');
    }

    public function getAllBySboAccountIdQuery()
    {
        return (new \DbQuery())
            ->select('*')
            ->from((new ServiceConfigurationModel())->getTableName(), 'service_configuration')
            ->where('service_configuration.value = :id_sbo_account')
            ->where('service_configuration.name = :name');
    }

    public function getUpdateQuery()
    {
        $queryPrepare = 'INSERT IGNORE INTO `'._DB_PREFIX_.(new ShippingboAccountModel())->getTableName().'` (`id_account`,`apiUrl`,`name`,`apiUser`,`apiToken`,`apiVersion`,`created_at`,`updated_at`) VALUES (:id_account,:apiUrl,:name,:apiUser,:apiToken,:apiVersion,:created_at,:updated_at)
        ON DUPLICATE KEY UPDATE
            `id_account` =  CASE WHEN `id_account` IS NULL THEN :id_account ELSE `id_account` END,
            `apiUrl` =  CASE WHEN :apiUrl IS NULL THEN `apiUrl` ELSE :apiUrl END,
            `name` =  CASE WHEN :name IS NULL THEN `name` ELSE :name END,
            `apiUser` =  CASE WHEN :apiUser IS NULL THEN `apiUser` ELSE :apiUser END,
            `apiToken` = CASE WHEN :apiToken IS NULL THEN `apiToken` ELSE :apiToken END,
            `apiVersion` = CASE WHEN :apiVersion IS NULL THEN `apiVersion` ELSE :apiVersion END,
            `updated_at` = :updated_at
';

        return $queryPrepare;
    }

    public function getTableName()
    {
        return (new ShippingboAccountModel())->getTableName();
    }

    public function getDeleteQuery()
    {
        return 'DELETE FROM '._DB_PREFIX_.$this->getTableName().' Where id_account = :id_account';
    }
}
